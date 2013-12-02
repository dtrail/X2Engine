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

/**
 * X2FlowAction that reassigns a record
 *
 * @package X2CRM.components.x2flow.actions
 */
class X2FlowRecordReassign extends X2FlowAction {

    public $title = 'Reassign Record';
    public $info = 'Assign the record to a user or group, or automatically using lead routing.';

    public function __construct(){
        $this->attachBehavior('LeadRoutingBehavior', array('class' => 'LeadRoutingBehavior'));
    }

    public function paramRules(){
        $leadRoutingModes = array(
            '' => 'Free For All',
            'roundRobin' => 'Round Robin Distribution',
            'roundRobin' => 'Sequential Distribution',
            'singleUser' => 'Direct User Assignment'
        );
        return array(
            'title' => Yii::t('studio', $this->title),
            'info' => Yii::t('studio', $this->info),
            'modelRequired' => 1,
            'options' => array(
                // array('name'=>'routeMode','label'=>'Routing Method','type'=>'dropdown','options'=>$leadRoutingModes),
                array('name' => 'user', 'label' => 'User', 'type' => 'dropdown', 'multiple' => 1, 'options' => array('auto' => Yii::t('studio', 'Use Lead Routing')) + X2Model::getAssignmentOptions(true, true)),
            // array('name'=>'onlineOnly','label'=>'Online Only?','optional'=>1,'type'=>'boolean','defaultVal'=>false),
                ));
    }

    public function execute(&$params){
        $model = $params['model'];
        if(!$model->hasAttribute('assignedTo')){
            return array(
                false,
                Yii::t('studio', get_class($model).' records have no attribute "assignedTo"')
            );
        }

        $user = $this->parseOption('user', $params);
        if($user === 'auto'){
            $assignedTo = $this->getNextAssignee();
        }elseif(CActiveRecord::model('User')->exists('username=?', array($user)) || CActiveRecord::model('Groups')->exists('id=?', array($user))){ // make sure the user exists
            $assignedTo = $user;
        }else{
            return array(false, Yii::t('studio', 'User '.$user.' does not exist'));
        }

        if($model->updateByPk(
                        $model->id, array('assignedTo' => $assignedTo))){

            if(is_subclass_of($model, 'X2Model')){
                return array(
                    true,
                    Yii::t('studio', 'View updated record: ').$model->getLink()
                );
            }else{
                return array(true, "");
            }
        }else{
            return array(false, "");
        }
    }

}
