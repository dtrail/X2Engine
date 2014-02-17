<?php

/*****************************************************************************************
 * X2CRM Open Source Edition is a customer relationship management program developed by
 * X2Engine, Inc. Copyright (C) 2011-2014 X2Engine Inc.
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
 * @package X2CRM.models 
 */
class Record {

    /**
     * Compiles new actions and contacts into a list for the "What's New" page
     * @param array $records
     * @param boolean $whatsNew
     * @return array 
     */
	public static function convert($records, $whatsNew=true) {
		$arr=array();
		
		foreach ($records as $record) {
			$user=UserChild::model()->findByAttributes(array('username'=>$record->updatedBy));
			if(isset($user)) {
				$name=$user->firstName." ".$user->lastName;
				$userId=$user->id;
			} else {
				$name='web admin';
				$userId=1;
			}
            $temp=array();
            if($record->hasAttribute('assignedTo')){
                $assignment=User::model()->findByAttributes(array('username'=>$record->assignedTo));
                $temp['assignedTo']=isset($assignment)?CHtml::link($assignment->firstName." ".$assignment->lastName,array('/profile/view','id'=>$assignment->id)):"";
            }else{
                $temp['assignedTo']='';
            }
            $linkObject = $record->asa("X2LinkableBehavior");
            if ($linkObject instanceof CBehavior) {
                $temp['#recordLink'] = $linkObject->link;
                $temp['#recordUrl'] = $linkObject->url;
            }
            else {
                if ($record->hasAttribute('name'))
                    $temp['#recordLink'] = $record->name;
                elseif($record->hasAttribute('id'))
                    $temp['#recordLink'] = '#'.$record->id;
                else
                    $temp['#recordLink'] = '';
            }
            if($record instanceof Contacts) {
				$temp['id']=$record->id;
				$temp['name']=$record->firstName.' '.$record->lastName;
				$temp['description']=$record->backgroundInfo;
				$temp['link']=array('/contacts/contacts/view','id'=>$record->id);
				$temp['type']='Contact';
				$temp['lastUpdated']=$record->lastUpdated;
				$temp['updatedBy']=CHtml::link($name,array('/profile/view','id'=>$userId));
				
				while(isset($arr[$temp['lastUpdated']]))
					$temp['lastUpdated']++;
				$arr[$temp['lastUpdated']]=$temp;
				
			} elseif($record instanceof Actions) {
				$temp['id']=$record->id;
				$temp['name']=empty($record->type)? Yii::t('actions','Action') : Yii::t('actions','Action: ').ucfirst($record->type);
				$temp['description']=$record->actionDescription;
				$temp['link']=array('/actions/actions/view','id'=>$record->id);
				$temp['type']='Action';
				$temp['lastUpdated']=$record->lastUpdated;
				$temp['updatedBy']=$name;
				while(isset($arr[$temp['lastUpdated']]))
					$temp['lastUpdated']++;
				$arr[$temp['lastUpdated']]=$temp;
			} else {
				$temp['id']=$record->id;
				$temp['name']=$record->name;
				if(!is_null($record->description))
					$temp['description']=$record->description;
				else
					$temp['description']="";
				
				$temp['lastUpdated']=$record->lastUpdated;
				$temp['updatedBy']=$name;
				
				if($record instanceof Opportunity) {
					$temp['link']=array('/opportunities/opportunities/view','id'=>$record->id);
					$temp['type']='Opportunity';
				}
				elseif($record instanceof Accounts) {
					$temp['link']=array('/accounts/accounts/view','id'=>$record->id);
					$temp['type']='Account';
				} elseif($record instanceof Quote || $record instanceof Product){
                    $temp['type']=get_class($record);
					$temp['link']=array(str_repeat('/'.strtolower(get_class($record)).'s',2).'/view','id'=>$record->id);
                }else {
                    $temp['type']=get_class($record);
					$temp['link']=array(str_repeat('/'.strtolower(get_class($record)),2).'/view','id'=>$record->id);
				}

				while(isset($arr[$temp['lastUpdated']]))
					$temp['lastUpdated']++;
				if($whatsNew)
					$arr[$temp['lastUpdated']]=$temp;
				else
					$arr[]=$temp;
			}
		}
		if($whatsNew){
			ksort($arr);
			return array_values(array_reverse($arr));
		}else{
			return array_values($arr);
		}
	}
}
