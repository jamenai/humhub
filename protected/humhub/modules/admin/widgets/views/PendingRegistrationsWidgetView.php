<?php

use humhub\modules\admin\assets\AdminPendingRegistrationsAsset;
use humhub\modules\admin\widgets\ExportButton;
use humhub\widgets\Button;
use humhub\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $searchModel \humhub\modules\admin\models\PendingRegistrationSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */
/** @var $types array */
AdminPendingRegistrationsAsset::register($this);
?>
<?= Html::beginTag('div', $options); ?>
    <h4><?= Yii::t('AdminModule.base', 'Pending user registrations') ?></h4>

    <div class="help-block">
        <?= Yii::t(
            'AdminModule.user',
            'The following list contains all pending sign-ups and invites.'
        ) ?>
    </div>

    <div class="pull-right">
        <?php if ($dataProvider->totalCount > 0): ?>
            <?=Button::danger(Yii::t('AdminModule.user', 'Delete All'))
                ->action('admin.PendingRegistrations.deleteAll', Url::toRoute(['/admin/pending-registrations/delete-all']))
                ->cssClass('delete-all')->
                confirm('<b>Delete</b> pending registrations?','Do you really want to delete pending registrations?');?>
        <?php endif; ?>
        <?= humhub\libs\Html::backButton(
            ['/admin/user/index'],
            ['label' => Yii::t('AdminModule.base', 'Back to user overview')]
        ) ?>
        <?= ExportButton::widget(['filter' => 'PendingRegistrationSearch']) ?>
    </div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'id' => 'grid',
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'cssClass' => 'select-on-check-one',
            'checkboxOptions' => function ($data) {
                return ['id' => $data->id];
            },
        ],
        'email',
        'originator.username',
        'language',
        'created_at',
        [
            'attribute' => 'source',
            'filter' => \yii\helpers\Html::activeDropDownList($searchModel, 'source', $types),
            'options' => ['width' => '40px'],
            'format' => 'raw',
            'value' => function ($data) use ($types) {
                return isset($types[$data->source]) ?: Html::encode($data->source);
            },
        ],
        [
            'header' => Yii::t('AdminModule.user', 'Actions'),
            'class' => 'yii\grid\ActionColumn',
            'template' => '{resend} {delete}',
            'buttons' => [
                'resend' => function ($url, $model, $key) {
                    return Html::a(
                        '<i class="fa fa-envelope"></i>',
                        Url::to(['resend', 'id' => $model->id]),
                        ['class' => 'btn btn-primary btn-xs tt']
                    );
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a(
                        '<i class="fa fa-trash"></i>',
                        Url::to(['delete', 'id' => $model->id]),
                        ['class' => 'btn btn-primary btn-xs tt']
                    );
                },
            ],
        ],

    ]
]) ?>
<?= Html:: endTag('div');
