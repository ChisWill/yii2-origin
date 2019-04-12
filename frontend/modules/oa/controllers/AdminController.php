<?php

namespace oa\controllers;

use Yii;
use common\helpers\Hui;
use common\helpers\Html;
use common\helpers\Inflector;
use common\helpers\ArrayHelper;
use oa\models\OaMenu;
use oa\models\AdminUser;
use oa\models\OaBonus;
use common\modules\rbac\models\AuthItem;

/**
 * @author ChisWill
 */
class AdminController extends \oa\components\Controller
{
    /**
     * @authname 管理员列表
     */
    public function actionList()
    {
        $html = AdminUser::getAdminListHtml();

        return $this->render('list', compact('html'));
    }

    /**
     * @authname 创建/修改管理员
     */
    public function actionSaveAdmin($id = null)
    {
        $user = AdminUser::findModel($id);
        $this->checkAccess($user);
        $user->tmpPassword = $user->password;
        // 避免在页面上显示密码
        $user->password = null;
        // 获取当前的所有角色
        $roles = AuthItem::getRoleQuery()->map('name', 'name');
        // 填充当前用户拥有的角色
        $authItem = new AuthItem;
        $authItem->roles = AdminUser::roles($id);

        if ($user->load()) {
            if ($user->saveAdmin()) {
                return success();
            } else {
                return error($user);
            }
        }

        return $this->render('saveAdmin', compact('user', 'authItem', 'roles'));
    }

    /**
     * @authname 删除管理员
     */
    public function actionAjaxDeleteAdmin()
    {
        $id = post('id');
        $this->checkAccess(AdminUser::findModel($id));

        if ($id != u()->id) {
            return parent::actionDelete();
        } else {
            return error('不能删除自己');
        }
    }

    /**
     * @authname 角色列表
     */
    public function actionRoleList()
    {
        $query = AuthItem::getRoleQuery();
        $roles = $query->all();
        $categoryMap = OaMenu::categoryMap();
        $html = $query->getTable([
            'name' => ['header' => '角色名称（规则）', 'width' => '15%', 'value' => function ($role) {
                $html = $role->name;
                if ($role->rule_name) {
                    $html .= '<br>（' . Html::finishSpan($role->rule_name) . '）';
                }
                return $html;
            }],
            ['header' => '拥有的权限', 'value' => function ($role) use ($roles, $categoryMap) {
                $childRoles = $childPermissions = [];
                $html = '';
                foreach ($role->children as $child) {
                    if (array_key_exists($child['child'], $roles)) {
                        $childRoles[] = $child;
                    } else {
                        $childPermissions[] = $child;
                    }
                }
                if ($childRoles) {
                    $html .= Html::likeSpan('角色') . '：';
                    $d = '';
                    foreach ($childRoles as $childRole) {
                        $html .= $d . $childRole['child'];
                        $d = '，';
                    }
                }
                if ($childPermissions) {
                    if ($childRoles) {
                        $html .= '<br>';
                    }
                    ArrayHelper::multisort($childPermissions, 'child');
                    $permissionGroup = [];
                    foreach ($childPermissions as $childPermission) {
                        $controller = explode('-', Inflector::camel2id($childPermission->child))[1];
                        $permissionGroup[$controller][] = $childPermission;
                    }
                    foreach ($permissionGroup as $controller => $permissions) {
                        $html .= Html::successSpan(ArrayHelper::getValue($categoryMap, $controller, '常规')) . '：';
                        $d = '';
                        foreach ($permissions as $permission) {
                            $html .= $d . Html::span($permission->childItem['description'], ['data-key' => $permission->child]);
                            $d = '，';
                        }
                        $html .= '<br>';
                    }
                }
                return $html;
            }],
            ['type' => ['edit' => 'editRole', 'delete' => 'ajaxDeleteRole']]
        ], [
            'addBtn' => ['createRole' => '创建角色']
        ]);

        return $this->render('roleList', compact('html'));
    }

    /**
     * @authname 创建角色
     */
    public function actionCreateRole()
    {
        $title = '创建角色';
        // 获取权限对象
        $auth = Yii::$app->authManager;
        // 获取当前的所有角色
        $roles = AuthItem::getRoleQuery()->map('name', 'name');
        // 获取当前的所有权限
        $permissions = AuthItem::getGroupPermissionData();
        // 获取模型
        $model = new AuthItem(['scenario' => 'createRole']);

        if ($model->load()) {
            $model->description = AuthItem::getCurrentAppId();
            if ($model->validate()) {
                $post = post('AuthItem', []);
                // 添加角色
                $role = $auth->createRole($model->name);
                $role->description = $model->description;
                $role->ruleName = $model->rule_name;
                $auth->add($role);
                // 获取角色和权限
                $roles = ArrayHelper::getValue($post, 'roles') ?: [];
                // 添加子角色
                foreach ($roles as $roleName) {
                    $childRole = $auth->getRole($roleName);
                    $auth->addChild($role, $childRole);
                }
                $permissions = ArrayHelper::getValue($post, 'permissions') ?: [];
                // 添加权限
                foreach ($permissions as $permissionName) {
                    $permission = $auth->getPermission($permissionName);
                    $auth->addChild($role, $permission);
                }
                return success();
            } else {
                return error($model);
            }
        }

        return $this->render('role', compact('title', 'roles', 'permissions', 'model'));
    }

    /**
     * @authname 编辑角色
     */
    public function actionEditRole($id)
    {
        $name = $id;
        $title = '编辑角色';
        // 获取权限对象
        $auth = Yii::$app->authManager;
        // 获取当前的所有角色
        $roles = AuthItem::getRoleQuery()->map('name', 'name');
        // 获取当前的所有权限
        $permissions = AuthItem::getGroupPermissionData();
        // 获取当前的角色
        $role = $auth->getRole($name);
        if (!$role) {
            throw new \yii\base\InvalidParamException('不存在该角色！');
        }
        // 获取模型
        $model = new AuthItem;
        $model->name = $model->oldRoleName = $role->name;
        $model->description = $role->description;
        $model->rule_name = $role->ruleName;
        $model->scenario = 'updateRole';
        $children = $auth->getChildren($role->name);
        foreach ($children as $child) {
            if ($child->type == AuthItem::TYPE_ROLE) {
                $model->roles[] = $child->name;
            } elseif ($child->type == AuthItem::TYPE_PERMISSION) {
                $model->permissions[] = $child->name;
            }
        }
        // 过滤掉不能添加为子集的角色
        AuthItem::filterLoopRoles($roles, $role);

        if ($model->load()) {
            if ($model->validate()) {
                $post = post('AuthItem', []);
                // 更改角色名
                $role->name = $model->name;
                $role->ruleName = $model->rule_name;
                $auth->update($name, $role);
                // 更改子角色以及权限
                $items = ['role', 'permission'];
                $methods = ['add', 'remove'];
                foreach ($items as $item) {
                    $postName = $item . 's';
                    $updateItems = ArrayHelper::getValue($post, $postName) ?: [];
                    list($add, $remove) = ArrayHelper::diff($model->$postName, $updateItems);
                    foreach ($methods as $method) {
                        foreach ($$method as $itemName) {
                            $getMethod = 'get' . ucfirst($item);
                            $authItem = $auth->$getMethod($itemName);
                            $updateMethod = $method . 'Child';
                            $auth->$updateMethod($role, $authItem);
                        }
                    }
                }
                return success();
            } else {
                return error($model);
            }
        }

        return $this->render('role', compact('title', 'roles', 'permissions', 'model'));
    }

    /**
     * @authname 查看角色权限
     */
    public function actionAjaxRoleInfo()
    {
        $roleList = get('roleList');
        $auth = Yii::$app->authManager;
        $roles = [];
        foreach ($roleList as $key => $role) {
            $roles = array_merge($roles, array_keys($auth->getPermissionsByRole($role)));
        }
        $roles = array_unique($roles);

        return success($roles);
    }

    /**
     * @authname 删除角色
     */
    public function actionAjaxDeleteRole()
    {
        $name = post('name');
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);

        if ($role && $auth->remove($role)) {
            return success('删除成功！');
        } else {
            return error('删除失败！');
        }
    }

    /**
     * @authname 权限列表
     */
    public function actionPermissionList()
    {
        $query = AuthItem::getAuthItemQuery();
        $categoryMap = OaMenu::categoryMap();
        $html = $query->getTable([
            'name' => ['header' => '动作', 'value' => function ($item) {
                $name = Inflector::camel2id($item->name);
                $namePieces = explode('-', $name);
                unset($namePieces[0], $namePieces[1]);
                return lcfirst(Inflector::id2camel(implode('-', $namePieces)));
            }],
            'description' => ['type' => 'text', 'value' => function ($item) {
                return Hui::textInput(null, $item->description);
            }],
            'rule_name' => ['type' => 'text', 'value' => function ($item) {
                return Hui::textInput(null, $item->rule_name, ['placeholder' => '规则名或是规则类名']);
            }],
            ['type' => ['delete']]
        ], [
            'beforeRow' => function ($item) use (&$controllerName, $categoryMap) {
                $name = Inflector::camel2id($item->name);
                $namePieces = explode('-', $name);
                if ($controllerName != $namePieces[1]) {
                    $controllerName = $namePieces[1];
                    return Html::tag('tr', Html::tag('th', ArrayHelper::getValue($categoryMap, $controllerName, '常规'), ['colspan' => 4, 'class' => 'text-c']));
                }
            },
            'isSort' => false,
            'paging' => false,
            'addBtn' => ['addPermission' => '添加权限'],
            'ajaxUpdateAction' => 'ajaxUpdatePermission'
        ]);

        return $this->render('permissionList', compact('html'));
    }

    /**
     * @authname 添加权限
     */
    public function actionAddPermission()
    {
        // 获取已经保存的权限信息
        $permissionMap = AuthItem::getPermissionQuery()->map('name', 'description');
        $models = [];
        // 获取文件中所有权限
        $actions = AuthItem::getFileActionList(['site']);
        foreach ($actions as $action => $description) {
            // 过滤已经保存的权限
            if (!array_key_exists($action, $permissionMap)) {
                $model = new AuthItem;
                $model->name = $action;
                $model->description = $description;
                $models[] = $model;
            }
        }

        if (req()->isPost) {
            if (AuthItem::loadMultiple($models, post()) && AuthItem::validateMultiple($models)) {
                $auth = Yii::$app->authManager;
                foreach ($models as $index => $model) {
                    $permission = $auth->createPermission($model->name);
                    $permission->description = $model->description;
                    $permission->ruleName = $model->rule_name;
                    $auth->add($permission);
                }
                return success();
            } else {
                $errors = [];
                foreach ($models as $key => $model) {
                    if ($model->hasErrors()) {
                        $index = $key + 1;
                        $errors[] = "第{$index}行，" . current($model->getFirstErrors());
                    }
                }
                return error($errors);
            }
        } else {
            $i = -1;
            $html = self::getTable($models, [
                ['header' => '序号', 'value' => function () use (&$i) {
                    return ++$i + 1;
                }],
                'name' => ['header' => '动作', 'value' => function ($model) use (&$i, &$action) {
                    $namePieces = explode('-', Inflector::camel2id($model->name));
                    unset($namePieces[0]);
                    return array_shift($namePieces) . '：' . ($action = lcfirst(Inflector::id2camel(implode('-', $namePieces)))) .
                           Html::hiddenInput("AuthItem[$i][name]", $model->name);
                }],
                'description' => ['header' => '描述', 'value' => function ($model) use (&$i, &$action) {
                    return Hui::textInput("AuthItem[$i][description]", $model->description ?: $action);
                }],
                'rule_name' => ['header' => '规则', 'value' => function ($model) use (&$i) {
                    return Hui::textInput("AuthItem[$i][rule_name]", $model->rule_name, ['placeholder' => '规则名或是规则类名']);
                }]
            ], [
                'ajaxLayout' => '{items}'
            ]);
        }

        return $this->render('addPermission', compact('html'));
    }

    /**
     * @authname 修改权限
     */
    public function actionAjaxUpdatePermission()
    {
        $auth = Yii::$app->authManager;
        $params = post('params');

        try {
            $authItem = AuthItem::findOne($params['key']);
            $authItem->$params['field'] = $params['value'];
            if ($authItem->validate()) {
                $permission = $auth->createPermission($params['key']);
                $permission->ruleName = $authItem->rule_name;
                $permission->description = $authItem->description;
                $auth->update($params['key'], $permission);
                return success();
            } else {
                return error($authItem);
            }
        } catch (\Exception $e) {
            throwex($e);
        }
    }

    /**
     * @authname 员工业绩
     */
    public function actionBonusList()
    {
        $query = (new OaBonus)->listQuery();

        $html = $query->getTable([
            'user.realname',
            'score',
            'created_at' => ['header' => '月份', 'value' => function ($row) {
                return substr($row->created_at, 0, 7);
            }]
        ], [
            'addBtn' => ['addScore' => '录入业绩'],
            'extraBtn' => ['bonusDetailList' => '明细列表'],
            'searchColumns' => [
                'user.id' => ['type' => 'select' , 'items' => function () {
                    return ['' => '选择员工'] + AdminUser::find()->active()->andWhere(u()->isMaster ?: ['id' => u()->id])->map('id', 'realname');
                }],
                'date' => ['header' => '月份', 'type' => 'dateRange']
            ]
        ]);

        return $this->render('bonusList', compact('html'));
    }

    /**
     * @authname 业绩明细列表
     */
    public function actionBonusDetailList()
    {
        $query = (new OaBonus)->detailQuery();
        $countQuery = clone $query;
        $countData = $countQuery->select(['user.realname', 'SUM(score) score', 'user_id'])->groupBy('user_id')->asArray()->all();
        $map = ArrayHelper::map($countData, 'user_id', 'score');
        $html = $query->getTable([
            'user.realname',
            'score',
            'comment' => ['type' => 'text'],
            'created_at' => function ($row) {
                return substr($row->created_at, 0, 10);
            }
        ], [
            'extraBtn' => ['bonusList' => '返回'],
            'ajaxReturn' => $map,
            'searchColumns' => [
                'user.id' => ['type' => 'select' , 'items' => function () {
                    return ['' => '选择员工'] + AdminUser::find()->active()->andWhere(u()->isMaster ?: ['id' => u()->id])->map('id', 'realname');
                }],
                'comment',
                'date' => ['header' => '月份', 'type' => 'dateRange']
            ]
        ]);

        return $this->render('bonusDetailList', compact('html', 'countData'));
    }

    /**
     * @authname 绩效分列表
     */
    public function actionPerformanceScore()
    {
        $query = AdminUser::find()->where(['position' => AdminUser::POSITION_DEV])->active();
        $scores = OaBonus::find()
            ->select(['user_id', 'SUM(score) AS score'])
            ->where(['user_id' => $query->map('id', 'id'), 'type' => OaBonus::TYPE_PERFORMANCE])
            ->groupBy('user_id')
            ->indexBy('user_id')
            ->asArray()
            ->all();

        $html = $query->select(['id', 'realname'])->getTable([
            'realname',
            'score' => ['header' => '累计绩效分', 'value' => function ($row) use ($scores) {
                return ArrayHelper::getValue($scores, $row->id, ['score' => 0])['score'];
            }],
            ['type' => ['view' => 'viewPerformanceHistory']]
        ], [
            'addBtn' => ['addPerformanceScore' => '录入']
        ]);

        return $this->render('performanceScore', compact('html'));
    }

    /**
     * @authname 录入绩效分
     */
    public function actionAddPerformanceScore()
    {
        $model = new OaBonus;

        if ($model->load()) {
            $model->type = OaBonus::TYPE_PERFORMANCE;
            if ($model->save()) {
                return success();
            } else {
                return error($model);
            }
        }

        return $this->render('addPerformanceScore', compact('model'));
    }

    /**
     * @authname 绩效分历史记录
     */
    public function actionViewPerformanceHistory($id)
    {
        $model = AdminUser::findOne($id);
        $query = OaBonus::find()
            ->where(['user_id' => $id, 'type' => OaBonus::TYPE_PERFORMANCE])
            ->orderBy('id DESC');
        $html = $query->getTable([
            'score' => ['header' => '绩效分', 'width' => '100px'],
            'comment',
            'created_at' => ['header' => '时间', 'width' => '150px']
        ]);

        return $this->render('viewPerformanceScore', compact('model', 'html'));
    }

    /**
     * @authname 录入业绩
     */
    public function actionAddScore()
    {
        $model = new OaBonus;

        if ($model->load()) {
            if ($model->save()) {
                return success();
            } else {
                return error($model);
            }
        }
        return $this->render('addScore', compact('model'));
    }

    /**
     * @authname 快捷修改员工信息
     */
    public function actionAjaxUpdateAdmin()
    {
        return parent::actionAjaxUpdate();
    }

    private function checkAccess($user)
    {
        if ($user->power > u()->power) {
            throwex('你不能对其操作！');
        }
    }
}
