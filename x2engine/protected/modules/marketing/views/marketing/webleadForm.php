<?php
/*****************************************************************************************
 * X2CRM Open Source Edition is a customer relationship management program developed by
 * X2Engine, Inc. Copyright (C) 2011-2013 X2Engine Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY X2ENGINE, X2ENGINE DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact X2Engine, Inc. P.O. Box 66752, Scotts Valley,
 * California 95067, USA. or at email address contact@x2engine.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * X2Engine" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by X2Engine".
 *****************************************************************************************/

$this->pageTitle = Yii::t('marketing','Web Lead Form');
$this->actionMenu = $this->formatMenu(array(
	array('label'=>Yii::t('marketing','All Campaigns'), 'url'=>array('index')),
	array('label'=>Yii::t('marketing','Create Campaign'), 'url'=>array('create')),
	array('label'=>Yii::t('contacts','Contact Lists'),'url'=>array('/contacts/lists')),
	array('label'=>Yii::t('marketing','Newsletters'),'url'=>array('weblist/index')),
	array('label'=>Yii::t('marketing','Web Lead Form')),
	array('label'=>Yii::t('marketing','Web Tracker'), 'url'=>array('webTracker'),'visible'=>(Yii::app()->params->edition==='pro')),
	array('label'=>Yii::t('app','X2Flow'),'url'=>array('/studio/flowIndex'),'visible'=>(Yii::app()->params->edition==='pro')),
));
?>
<div class="page-title icon marketing"><h2><?php echo Yii::t('marketing','Web Lead Form'); ?></h2></div>
<div class="form">
<?php echo Yii::t('marketing','Create a public form to receive new contacts.'),'<br>',
	Yii::t('marketing','If no lead routing has been configured, all new contacts will be assigned to "Anyone".'); ?>
</div>
<?php
$this->renderPartial ('application.components.views._createWebForm', 
    array(
        'forms'=>$forms,
        'webFormType'=>'weblead'
    )
);
