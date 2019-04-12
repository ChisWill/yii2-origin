<?php use common\helpers\Date; ?>
<?php \common\modules\manual\assets\ManualHistoryAsset::register($this) ?>

<div class="history-body">
	<div class="history-left">
		<div class="history-left-title">
			<span class="h-left-count"><?= count($history) ?></span>
			条修改记录
		</div>
		<div class="history-left-inner">
			<ul class="history-list">
				<?php foreach ($history as $key => $model) : ?>
				<li class="history-list-li" data-id="<?= $model['id'] ?>" data-menu-id="<?= $model['menu_id'] ?>" data-title="<?= $model->article['name'] ?>">
					<div class="history-avatar"><img src="<?= $model->user['face'] ?>"></div>
					<div class="history-message"><?= $model->getActionValue() ?>（<?= date('Y-m-d', strtotime($model['created_at'])) ?>）</div>
					<div class="history-meta"><?= Date::age($model['created_at'])?>  by <?= $model->user['username'] ?></div>
				</li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
	
	<div class="history-right">
		<div class="history-r-body">
			<div class="history-r-header">
		        <p class="history-r-message">
		        	<span class="icon icon-git-commit"></span>
		        	<span id="versionAction"></span> 文章：【<span id="versionId"></span>】
		        </p>

		        <p class="history-r-meta" id="versionDescription">
		            &nbsp;
		        </p>
		        <label class="w-btn btn-success btn-m">
		            <button class="btn-input reset">恢复到此版本</button>
		        </label>
		    </div>
		    <div class="history-files">
		    	<div class="history-files-con">
		    		<div class="history-files-header">
	                    <span class="pull-right">
	                    	<span class="text-success">增加 <span>0</span> 行</span>,
	                    	<span class="text-danger">删除 <span>0</span> 行</span>
	                    </span>
	                </div>
		    	</div>
		    	<div class="history-files-patch"></div>
		    </div>
		</div>
	</div>
</div>