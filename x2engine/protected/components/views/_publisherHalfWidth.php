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
?>
<div class="form publisher" id="log-a-call-form">
    <div id="log-a-call">
        <?php if($showQuickNote){ ?>
            <div class="row">
                <?php echo CHtml::label(Yii::t('app','Quick Note'), 'quickNote', array('style' => 'display:inline-block;')); ?>
                <?php
                echo CHtml::dropDownList('quickNote', '', array_merge(array('' => '-'), Dropdowns::getItems(117)), array(
                    'ajax' => array(
                        'type' => 'GET', //request type
                        'url' => Yii::app()->controller->createUrl('/site/dynamicDropdown'),
                        'data' => 'js:{"val":$(this).val(),"dropdownId":"117"}',
                        'update' => '#quickNote2',
                        'complete' => 'function() { $("#Actions_actionDescription").val(""); } '
                        )));
                ?>
                <?php echo CHtml::dropDownList('quickNote2', '', array('' => '-')); ?>
            </div>
        <?php } ?>
    </div>
    <div class="row">
        <?php
        echo CHtml::ajaxSubmitButton(Yii::t('app', 'Save'), array('/actions/actions/PublisherCreate'), array(
            'beforeSend' => "function() {
                        if($('#Actions_actionDescription').val() == '') {
                            alert(".json_encode(Yii::t('actions', 'Please enter a description.')).");
                            return false;
                        } else {
                            // show saving... icon
                            \$('.publisher-text').animate({opacity: 0.0});
                            \$('#publisher-saving-icon').animate({opacity: 1.0});
                        }

                        return true; // form is sane: submit!
                     }",
            'success' => "function(data) {
                    if(!data){
                        x2.publisher.updates();
                        x2.publisher.reset();

                        // event detected by x2chart.js
                        $(document).trigger ('newlyPublishedAction');

                    \$('#publisher-error').html('');
                        \$('.publisher-text').animate({opacity: 1.0});
                        \$('#publisher-saving-icon').animate({opacity: 0.0});}else{
                    \$('#publisher-error').html(data);
                    }
                }",
            'type' => 'POST',
                ), array('id' => 'save-publisher', 'class' => 'x2-button', 'tabindex' => 21));
        ?>
        <div class="text-area-wrapper" style="margin-right:75px;">
            <?php echo $form->textArea($model, 'actionDescription', array('rows' => 3, 'cols' => 40, 'tabindex' => 20)); ?>
        </div>
    </div>
    <?php if(Yii::app()->user->isGuest){ ?>
        <div class="row">
            <?php
            $this->widget('CCaptcha', array(
                'captchaAction' => '/actions/actions/captcha',
                'buttonOptions' => array(
                    'style' => 'display:block;',
                ),
            ));
            ?>
            <?php echo $form->textField($model, 'verifyCode'); ?>
        </div>
    <?php } ?>
    <?php echo CHtml::hiddenField('SelectedTab', ''); // currently selected tab  ?>
    <?php echo $form->hiddenField($model, 'associationType'); ?>
    <?php echo $form->hiddenField($model, 'associationId'); ?>
    <div id="publisher-error" style="color:red;"></div>
    <div id="action-event-panel">
        <div class="row">
            <div class="cell">
                <?php echo CHtml::activeLabel($model,'dueDate',array('id' =>  'action-due-date-label', 'style' => 'display: none;')); ?>
                <?php echo CHtml::activeLabel($model,'dueDate',array('label'=>Yii::t('actions', 'Start Date'),'id' => 'action-start-date-label', 'style' => 'display: none;')); ?>
                <?php echo CHtml::activeLabel($model,'dueDate',array('label'=>Yii::t('actions', 'Time started'),'id' => 'action-start-time-label', 'style' => 'display: none;')); ?>

                <?php
                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $this->widget('CJuiDateTimePicker', array(
                    'model' => $model, //Model object
                    'attribute' => 'dueDate', //attribute name
                    'mode' => 'datetime', //use "time","date" or "datetime" (default)
                    'options' => array(
                        'dateFormat' => Formatter::formatDatePicker('medium'),
                        'timeFormat' => Formatter::formatTimePicker(),
                        'ampm' => Formatter::formatAMPM(),
                        'changeMonth' => true,
                        'changeYear' => true,
                    ), // jquery plugin options
                    'language' => (Yii::app()->language == 'en') ? '' : Yii::app()->getLanguage(),
                    'htmlOptions' => array('id'=>'action-due-date','onClick' => "$('#ui-datepicker-div').css('z-index', '20');"), // fix datepicker so it's always on top
                ));

                echo CHtml::activeLabel($model,'completeDate',array('label'=>Yii::t('actions', 'End Date'),'id' => 'action-end-date-label', 'style' => 'display: none;'));
                echo CHtml::activeLabel($model,'completeDate', array('label'=>Yii::t('actions', 'Time ended'),'id' => 'action-end-time-label', 'style' => 'display: none;'));

                $model->dueDate = Formatter::formatDateTime(time());
                Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $this->widget('CJuiDateTimePicker', array(
                    'model' => $model, //Model object
                    'attribute' => 'completeDate', //attribute name
                    'mode' => 'datetime', //use "time","date" or "datetime" (default)
                    'options' => array(
                        'dateFormat' => Formatter::formatDatePicker('medium'),
                        'timeFormat' => Formatter::formatTimePicker(),
                        'ampm' => Formatter::formatAMPM(),
                        'changeMonth' => true,
                        'changeYear' => true,
                    ), // jquery plugin options
                    'language' => (Yii::app()->language == 'en') ? '' : Yii::app()->getLanguage(),
                    'htmlOptions' => array(
                        'onClick' => "$('#ui-datepicker-div').css('z-index', '20');", // fix datepicker so it's always on top
                        'style' => 'display: none;',
                        'id' => 'action-complete-date',
                    ),
                ));
                ?>
            </div>
            <div class="cell event-panel-second-cell">
                <div id="action-duration" style="display:none;">
                    <div class="action-duration-input">
                        <input class="action-duration-display" type="number" min="0" max="99" name="timetrack-hours" />
                        <label for="timetrack-hours"><?php echo Yii::t('actions','hours'); ?></label>
                    </div>
                    <span class="action-duration-display">:</span>
                    <div class="action-duration-input">
                        <input class="action-duration-display" type="number" min="0" max="59" name="timetrack-minutes" />
                        <label for="timetrack-minutes"><?php echo Yii::t('actions','minutes'); ?></label>
                    </div>
                </div>
                <?php echo CHtml::activeLabel($model,'priority',array('id'=>'action-priority-label','style'=>'display: none;')); ?>
                <?php
                echo $form->dropDownList($model, 'priority', array(
                    '1' => Yii::t('actions', 'Low'),
                    '2' => Yii::t('actions', 'Medium'),
                    '3' => Yii::t('actions', 'High'))
                        ,
                    array('id'=>'action-priority')
                );
                ?>
                <?php echo $form->label($model, 'color',array('id'=>'action-color-label')); ?>
                    <?php echo $form->dropDownList($model, 'color', Actions::getColors(),array('id'=>'action-color-dropdown')); ?>
            </div>
            <?php /* Assinged To */ ?>
            <div class="cell">

                <?php /* Users */ ?>
                <?php echo $form->label($model, 'assignedTo',array('id'=>'action-assigned-to-label')); ?>
                <?php echo $form->dropDownList($model, 'assignedTo', X2Model::getAssignmentOptions(true,true), array('id' => 'actionsAssignedToDropdown')); ?>
            </div>
            <div class="cell">
                <?php echo $form->label($model, 'visibility',array('id'=>'action-visibility-label')); ?>
                <?php $model->visibility = 1; // default visibility = public  ?>
                <?php echo $form->dropDownList($model, 'visibility', array(0 => Yii::t('actions', 'Private'), 1 => Yii::t('actions', 'Public'), 2 => Yii::t('actions', "User's Group")),array('id'=>'action-visibility-dropdown')); ?>
            </div>
        </div>
    </div>
    <div id="new-action">
    </div>
    <div id="new-comment">
    </div>
    <div id="new-event">
    </div>
</div>
