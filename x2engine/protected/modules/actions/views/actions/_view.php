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

Yii::app()->clientScript->registerScript('deleteActionJs', "
function deleteAction(actionId) {

	if(confirm('".Yii::t('app', 'Are you sure you want to delete this item?')."')) {
		$.ajax({
			url: '".CHtml::normalizeUrl(array('/actions/actions/delete'))."/'+actionId+'?ajax=1',
			type: 'POST',
			//data: 'id='+actionId,
			success: function(response) {
				if(response=='Success')
					$('#history-'+actionId).fadeOut(200,function() { $('#history-'+actionId).remove(); });

					// event detected by x2chart.js
					$(document).trigger ('deletedAction');
				}
		});
	}
}
", CClientScript::POS_HEAD);
$themeUrl = Yii::app()->theme->getBaseUrl();
if(empty($data->type)){
    if($data->complete == 'Yes')
        $type = 'complete';
    else if($data->dueDate < time())
        $type = 'overdue';
    else
        $type = 'action';
} else
    $type = $data->type;

if($type == 'workflow'){

    $workflowRecord = X2Model::model('Workflow')->findByPk($data->workflowId);
    $stageRecords = X2Model::model('WorkflowStage')->findAllByAttributes(
            array('workflowId' => $data->workflowId), new CDbCriteria(array('order' => 'id ASC'))
    );

    // see if this stage even exists; if not, delete this junk
    if($workflowRecord === null || $data->stageNumber < 1 || $data->stageNumber > count($stageRecords)){
        $data->delete();
        return;
    }
}

// if($type == 'call') {
// $type = 'note';
// $data->type = 'note';
// }
?>



<div class="view" id="history-<?php echo $data->id; ?>">
    <!--<div class="deleteButton">
<?php //echo CHtml::link('[x]',array('deleteNote','id'=>$data->id)); //,array('class'=>'x2-button')  ?>
    </div>-->
    <div class="icon <?php echo $type; ?>"></div>
    <div class="header">
<?php
if(empty($data->type) || $data->type == 'weblead'){
    if($data->complete == 'Yes'){
        echo "<span style='color:grey;cursor:pointer' class='action-frame-link' data-action-id='{$data->id}'>".Yii::t('actions', 'Completed:')." </span>".Formatter::formatCompleteDate($data->completeDate);
    }else{
        if(!empty($data->dueDate)){
            echo "<span style='color:grey;cursor:pointer' class='action-frame-link' data-action-id='{$data->id}'>".Yii::t('actions', 'Due:')." </span>".Actions::parseStatus($data->dueDate).'</b>';
        }elseif(!empty($data->createDate)){
            echo "<span style='color:grey;cursor:pointer' class='action-frame-link' data-action-id='{$data->id}'>".Yii::t('actions', 'Created:')." </span>".Formatter::formatLongDateTime($data->createDate).'</b>';
        }else{
            echo "&nbsp;";
        }
    }
}elseif($data->type == 'attachment'){
    if($data->completedBy == 'Email')
        echo Yii::t('actions', 'Email Message:').' '.Formatter::formatCompleteDate($data->completeDate);
    else
        echo Yii::t('actions', 'Attachment:').' '.Formatter::formatCompleteDate($data->completeDate);
    //User::getUserLinks($data->completedBy);

    echo ' ';

    //if ($data->complete=='Yes')
    //echo Formatter::formatDate($data->completeDate);
    //else
    //echo Actions::parseStatus($data->dueDate);
} elseif($data->type == 'workflow'){
    // $actionData = explode(':',$data->actionDescription);
    echo Yii::t('workflow', 'Workflow:').'<b> '.$workflowRecord->name.'/'.$stageRecords[$data->stageNumber - 1]->name.'</b> ';
}elseif(in_array($data->type, array('email', 'emailFrom', 'email_quote', 'email_invoice'))){
    echo Yii::t('actions', 'Email Message:').' '.Formatter::formatCompleteDate($data->completeDate);
}elseif($data->type == 'quotes'){
    echo Yii::t('actions', 'Quote:').' '.Formatter::formatCompleteDate($data->createDate);
}elseif(in_array($data->type, array('emailOpened', 'emailOpened_quote', 'email_opened_invoice'))){
    echo Yii::t('actions', 'Email Opened:').' '.Formatter::formatCompleteDate($data->completeDate);
}elseif($data->type == 'webactivity'){
    echo Yii::t('actions', 'This contact visited your website');
}elseif($data->type == 'note'){
    echo Formatter::formatCompleteDate($data->completeDate);
}elseif($data->type == 'call'){
    echo Yii::t('actions', 'Call:').' '.($data->completeDate == $data->dueDate
            ? Formatter::formatCompleteDate($data->completeDate)
            : Formatter::formatTimeInterval($data->dueDate,$data->completeDate,'{start}; {decHours} '.Yii::t('app','hours')));
}elseif($data->type == 'event'){
    echo '<b>'.CHtml::link(Yii::t('calendar', 'Event').': ', '#', array('class' => 'action-frame-link', 'data-action-id' => $data->id));
    if($data->allDay){
        echo Formatter::formatLongDate($data->dueDate);
        if($data->completeDate)
            echo ' - '.Formatter::formatLongDate($data->completeDate);
    } else{
        echo Formatter::formatLongDateTime($data->dueDate);
        if($data->completeDate)
            echo ' - '.Formatter::formatLongDateTime($data->completeDate);
    }
    echo '</b>';
}elseif($data->type == 'time'){
    echo Formatter::formatTimeInterval($data->dueDate,$data->completeDate);
}
?>
        <div class="buttons">
        <?php
        if(!Yii::app()->user->isGuest){
            if(empty($data->type) || $data->type == 'weblead'){
                if($data->complete == 'Yes' && Yii::app()->user->checkAccess('ActionsUncomplete',array('assignedTo'=>$data->assignedTo)))
                    echo CHtml::link(CHtml::image($themeUrl.'/images/icons/Uncomplete.png'), '#', array('class' => 'uncomplete-button', 'title' => $data->id, 'data-action-id' => $data->id));
                elseif(Yii::app()->user->checkAccess('ActionsComplete',array('assignedTo'=>$data->assignedTo))){
                    echo CHtml::link(CHtml::image($themeUrl.'/images/icons/Complete.png'), '#', array('class' => 'complete-button', 'title' => $data->id, 'data-action-id' => $data->id));
                }
            }
            if($data->type != 'workflow'){
                if(Yii::app()->user->checkAccess('ActionsUpdate',array('assignedTo'=>$data->assignedTo))){
                    echo $data->type != 'attachment' ? ' '.CHtml::link(CHtml::image($themeUrl.'/images/icons/Edit.png'), array('/actions/actions/update', 'id' => $data->id, 'redirect' => 1), array()).' ' : "";
                }
                if(Yii::app()->user->checkAccess('ActionsDelete',array('assignedTo'=>$data->assignedTo))){
                    echo ' '.CHtml::link(CHtml::image($themeUrl.'/images/icons/Delete_Activity.png'), '#', array('onclick' => 'deleteAction('.$data->id.'); return false'));
                }
            }
        }
        ?>
        </div>
    </div>
    <div class="description">
<?php
if($type == 'attachment' && $data->completedBy != 'Email')
    echo Media::attachmentActionText(Yii::app()->controller->convertUrls($data->actionDescription), true, true);
else if($type == 'workflow'){

    if(!empty($data->stageNumber) && !empty($data->workflowId) && $data->stageNumber <= count($stageRecords)){
        if($data->complete == 'Yes')
            echo ' <b>'.Yii::t('workflow', 'Completed').'</b> '.Formatter::formatLongDateTime($data->completeDate);
        else
            echo ' <b>'.Yii::t('workflow', 'Started').'</b> '.Formatter::formatLongDateTime($data->createDate);
    }
    if(isset($data->actionDescription))
        echo '<br>'.$data->actionDescription;
} elseif($type == 'webactivity'){
    if(!empty($data->actionDescription))
        echo $data->actionDescription, '<br>';
    echo date('Y-m-d H:i:s', $data->completeDate);
} elseif(in_array($data->type, array('email', 'emailFrom', 'email_quote', 'email_invoice', 'emailOpened', 'emailOpened_quote', 'emailOpened_invoice'))){

    $legacy = false;
    if(!preg_match(InlineEmail::insertedPattern('ah', '(.*)', 1, 'mis'), $data->actionDescription, $matches)){
        // Legacy pattern:
        preg_match('/<b>(.*?)<\/b>(.*)/mis', $data->actionDescription, $matches);
        $legacy = true;
    }
    if(!empty($matches)){
        $header = $matches[1];
        $body = '';
    }else{
        if(empty($data->subject)){
            $header = "No subject found";
            $body = "(Error displaying email)";
        }else{
            $header = $data->subject."<br>";
            $body = $data->actionDescription;
        }
    }
    if($type == 'emailOpened'){
        echo "Contact has opened the following email:<br />";
    }
    if(!Yii::app()->user->isGuest){
        echo $legacy ? '<strong>'.$header.'</strong> '.$body : $header.$body;
    }else{
        echo $body;
    }
    echo ($legacy ? '<br />' : '').CHtml::link('[View email]', '#', array('onclick' => 'return false;', 'id' => $data->id, 'class' => 'email-frame'));
}elseif($data->type == 'quotes'){
    $quotePrint = (bool)  preg_match('/^\d+$/',$data->actionDescription);
    $objectId = $quotePrint ? $data->actionDescription : $data->id;
    echo CHtml::link('[View quote]', 'javascript:void(0);', array('onclick' => 'return false;', 'id' => $objectId, 'class' => $quotePrint ? 'quote-print-frame' : 'quote-frame'));
} else
    echo Yii::app()->controller->convertUrls(CHtml::encode($data->actionDescription)); // convert LF and CRLF to <br />
?>
    </div>
    <div class="footer">
        <?php
        if(isset($relationshipFlag) && $relationshipFlag){
            $relString=" | ".X2Model::getModelLink($data->associationId,X2Model::getModelName($data->associationType));
        }else{
            $relString="";
        }
        if(empty($data->type) || $data->type == 'weblead' || $data->type == 'workflow'){
            if($data->complete == 'Yes'){
                echo Yii::t('actions', 'Completed by {name}', array('{name}' => User::getUserLinks($data->completedBy))).$relString;
            }else{
                $userLink = User::getUserLinks($data->assignedTo);
                $userLink = empty($userLink) ? Yii::t('actions', 'Anyone') : $userLink;
                echo Yii::t('actions', 'Assigned to {name}', array('{name}' => $userLink)).$relString;
            }
        }else if(in_array($data->type,array('note','call','emailOpened','time'))){
            echo $data->completedBy == 'Guest' ? "Guest" : User::getUserLinks($data->completedBy).$relString;
            // echo ' '.Formatter::formatDate($data->completeDate);
        }else if($data->type == 'attachment' && $data->completedBy != 'Email'){
            echo Yii::t('media', 'Uploaded by {name}', array('{name}' => User::getUserLinks($data->completedBy))).$relString;
        }else if(in_array($data->type, array('email', 'emailFrom')) && $data->completedBy != 'Email'){
            echo Yii::t('media', ($data->type == 'email' ? 'Sent by {name}' : 'Sent to {name}'), array('{name}' => User::getUserLinks($data->completedBy))).$relString;
        }
        ?>
    </div>

</div>
