<?php

namespace common\components;

/**
 * ARModel查询的基类
 *
 * @author ChisWill
 */
class ARQuery extends \yii\db\ActiveQuery
{
    use \common\traits\QueryTrait;

    /**
     * 增加逻辑有效条件
     * 
     * @return object
     */
    public function active($alias = null)
    {
        return $this->andWhere([$alias ?: key($this->from) . '.state' => \common\components\ARModel::STATE_VALID]);
    }

    /**
     * 增加获取当前用户的所属条件
     * 
     * @return object
     */
    public function mine($alias = null)
    {
        return $this->andWhere([$alias ?: key($this->from) . '.user_id' => u()->id]);
    }

    /**
     * 设置当前后台用户绑定的前台用户条件
     * ps.为确保条件的正确组装，建议将此方法放在最后进行调用
     * eg:$query->where('id > 1')->orWhere('age < 33')->children()->all();
     * 
     * @param  string|array $alias 关联前台用户表的别名
     * @return object
     */
    public function children($alias = 'user.admin_id')
    {
        if (!u()->isAdmin) {
            $this->andWhere([$alias => u()->id]);
        }
        return $this;
    }

    /**
     * 与 common\widgets\Table 组件配套使用，可以自动增加关联表的搜索条件
     *
     * @param  string $name 搜索参数的name前缀值
     * @return object
     */
    public function andTableSearch($name = 'search')
    {
        foreach (get($name, []) as $key => $value) {
            if (strpos($key, '.') !== false) {
                list($alias, $field) = explode('.', $key);
                $column = $alias . '.' . $field;
                if (strpos($field, 'id') !== false || strpos($field, 'state') !== false || strpos($field, 'status') !== false) {
                    $this->andFilterWhere([$column => $value]);
                } else {
                    $this->andFilterWhere(['like', $column, $value]);
                }
            }
        }
        return $this;
    }
}
