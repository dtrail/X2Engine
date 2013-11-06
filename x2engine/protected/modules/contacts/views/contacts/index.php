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

$menuItems = array(
	array('label'=>Yii::t('contacts','All Contacts'),'url'=>array('index')),
	array('label'=>Yii::t('contacts','Lists'),'url'=>array('lists')),
	array('label'=>Yii::t('contacts','Create Contact'),'url'=>array('create')),
	array('label'=>Yii::t('contacts','Create List'),'url'=>array('createList')),
	array('label'=>Yii::t('contacts','View List')),
    array('label'=>Yii::t('contacts','Import Contacts'),'url'=>array('importExcel')),
	array('label'=>Yii::t('contacts','Export to CSV'),'url'=>array('exportContacts')),
    array('label'=>Yii::t('contacts','Contact Map'),'url'=>array('googleMaps')),
    array('label'=>Yii::t('contacts','Saved Maps'),'url'=>array('savedMaps')),
    //array('label'=>Yii::t('contacts','Saved Searches'),'url'=>array('savedSearches'))
);

$heading = '';

if($this->route=='contacts/contacts/index') {
	$heading = Yii::t('contacts','All Contacts');
	$dataProvider = $model->searchAll();
	unset($menuItems[0]['url']);
	unset($menuItems[3]);
	unset($menuItems[4]);
} elseif($this->route=='contacts/contacts/myContacts') {
	$heading = Yii::t('contacts','My Contacts');
	$dataProvider = $model->searchMyContacts();
} elseif($this->route=='contacts/contacts/newContacts') {
	$heading = Yii::t('contacts','Today\'s Contacts');
	$dataProvider = $model->searchNewContacts();
}

$opportunityModule = Modules::model()->findByAttributes(array('name'=>'opportunities'));
$accountModule = Modules::model()->findByAttributes(array('name'=>'accounts'));

if($opportunityModule->visible && $accountModule->visible)
	$menuItems[] = 	array('label'=>Yii::t('app', 'Quick Create'), 'url'=>array('/site/createRecords', 'ret'=>'contacts'), 'linkOptions'=>array('id'=>'x2-create-multiple-records-button', 'class'=>'x2-hint', 'title'=>Yii::t('app', 'Create a Contact, Account, and Opportunity.')));

$this->actionMenu = $this->formatMenu($menuItems);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').unbind('click').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('contacts-grid', {
		data: $(this).serialize()
	});
	return false;
});

$('#content').on('mouseup','#contacts-grid a',function(e) {
	document.cookie = 'vcr-list=".$this->getAction()->getId()."; expires=0; path=/';
});

$('#createList').unbind('click').click(function() {
	var selectedItems = $.fn.yiiGridView.getChecked('contacts-grid','C_gvCheckbox');
	if(selectedItems.length > 0) {
		var listName = prompt('".addslashes(Yii::t('app','What should the list be named?'))."','');

		if(listName != '' && listName != null) {
			$.ajax({
				url:'".$this->createUrl('/contacts/createListFromSelection')."',
				type:'post',
				data:{listName:listName,modelName:'Contacts',gvSelection:selectedItems},
				success:function(response) { if(response != '') window.location.href=response; }
			});
		}
	}
	return false;
});
$('#addToList').unbind('click').click(function() {
	var selectedItems = $.fn.yiiGridView.getChecked('contacts-grid','C_gvCheckbox');

	var targetList = $('#addToListTarget').val();

	if(selectedItems.length > 0) {
		$.ajax({
			url:'".$this->createUrl('/contacts/addToList')."',
			type:'post',
			data:{listId:targetList,gvSelection:selectedItems},
			success:function(response) { if(response=='success') alert('".addslashes(Yii::t('app','Added items to list.'))."'); else alert(response); }
		});
	}
	return false;
});
",CClientScript::POS_READY);

// init qtip for contact names
Yii::app()->clientScript->registerScript('contact-qtip', '
function refreshQtip() {
	$(".contact-name").each(function (i) {
		var contactId = $(this).attr("href").match(/\\d+$/);

		if(contactId !== null && contactId.length) {
			$(this).qtip({
				content: {
					text: "'.addslashes(Yii::t('app','loading...')).'",
					ajax: {
						url: yii.scriptUrl+"/contacts/qtip",
						data: { id: contactId[0] },
						method: "get"
					}
				},
				style: {
				}
			});
		}
	});
}

$(function() {
	refreshQtip();
});
');
?>


<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
	'users'=>User::getNames(),
)); ?>
</div><!-- search-form -->
<form>
<?php

$this->widget('application.components.X2GridView', array(
	'id'=>'contacts-grid',
	'title'=>$heading,
	'buttons'=>array('advancedSearch','clearFilters','columnSelector','autoResize'),
	'template'=> 
        '<div id="x2-gridview-top-bar-outer" class="x2-gridview-fixed-top-bar-outer">'.
        '<div id="x2-gridview-top-bar-inner" class="x2-gridview-fixed-top-bar-inner">'.
        '<div id="x2-gridview-page-title" '.
         'class="page-title icon contacts x2-gridview-fixed-title">'.
        '{title}{buttons}{filterHint}{massActionButtons}{summary}{items}{pager}',
    'fixedHeader'=>true,
	'dataProvider'=>$dataProvider,
	// 'enableSorting'=>false,
	// 'model'=>$model,
	'filter'=>$model,
	'pager'=>array('class'=>'CLinkPager','maxButtonCount'=>10),
	// 'columns'=>$columns,
	'modelName'=>'Contacts',
	'viewName'=>'contacts',
	// 'columnSelectorId'=>'contacts-column-selector',
	'defaultGvSettings'=>array(
		'gvCheckbox' => 30,
		'name' => 125,
		'email' => 165,
		'leadSource' => 83,
		'leadstatus' => 91,
		'phone' => 107,
		'lastActivity' => 78,
		'gvControls' => 73,
	),
	'specialColumns'=>array(
		'name'=>array(
			'name'=>'name',
			'header'=>Yii::t('contacts','Name'),
			'value'=>'$data->link',
			'type'=>'raw',
		),
	),
    'massActions'=>array(
        'addToList', 'newList'
    ),
	'enableControls'=>true,
	'enableTags'=>true,
	'fullscreen'=>true,
));
?>

</form>
