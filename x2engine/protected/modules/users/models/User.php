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

// Yii::import('application.models.X2Model');

/**
 * This is the model class for table "x2_users".
 *
 * @package X2CRM.modules.users.models
 */
class User extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
     */
    public static function model($className = __CLASS__){
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName(){
        return 'x2_users';
    }

    public function behaviors(){
        return array_merge(parent::behaviors(), array(
                    'X2LinkableBehavior' => array(
                        'class' => 'X2LinkableBehavior',
                        'module' => 'users',
                        'viewRoute' => '/profile',
                    ),
                    'ERememberFiltersBehavior' => array(
                        'class' => 'application.components.ERememberFiltersBehavior',
                        'defaults' => array(),
                        'defaultStickOnClear' => false
                    )
                ));
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules(){
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('status', 'required'),
            array('firstName, lastName, username, password', 'required', 'on' => 'insert'),
            array('status, lastLogin, login', 'numerical', 'integerOnly' => true),
            array('firstName, username, title, updatedBy', 'length', 'max' => 20),
            array('lastName, department, officePhone, cellPhone, homePhone', 'length', 'max' => 40),
            array('password, address, emailAddress, recentItems, topContacts', 'length', 'max' => 100),
            array('lastUpdated', 'length', 'max' => 30),
            array('userKey', 'length', 'max' => 32, 'min' => 3),
            array('backgroundInfo', 'safe'),
            array('username', 'unique', 'allowEmpty' => false),
            array('username', 'match', 'pattern' => '/^\d+$/', 'not' => true), // No numeric usernames. That will break association with groups.
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, firstName, lastName, username, password, title, department, officePhone, cellPhone, homePhone, address, backgroundInfo, emailAddress, status, lastUpdated, updatedBy, recentItems, topContacts, lastLogin, login', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations(){
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'profile' => array(self::HAS_ONE, 'Profile', 'id'),
        );
    }

    public static function hasRole($user, $role){
        if(is_numeric($role)){
            $lookup = RoleToUser::model()->findByAttributes(array('userId' => $user, 'roleId' => $role));
            return isset($lookup);
        }else{
            $roleRecord = Roles::model()->findByAttributes(array('name' => $role));
            if(isset($roleRecord)){
                $lookup = RoleToUser::model()->findByAttributes(array('userId' => $user, 'roleId' => $roleRecord->id));
                return isset($lookup);
            }else{
                return false;
            }
        }
    }

    public static function getUsersDataProvider () {
        $usersDataProvider = new CActiveDataProvider('User', array(
                    'criteria' => array(
                        'condition' => 'status=1',
                        'order' => 'lastName ASC'
                    )
                ));
        return $usersDataProvider;
    }


    public static function getNames(){

        $userNames = array();
        $query = Yii::app()->db->createCommand()
                ->select('username, CONCAT(firstName," ",lastName) AS name')
                ->from('x2_users')
                ->where('status=1')
                ->order('name ASC')
                ->query();

        while(($row = $query->read()) !== false)
            $userNames[$row['username']] = $row['name'];
        natcasesort($userNames);

        return array('Anyone' => Yii::t('app', 'Anyone')) + $userNames;
    }

    public static function getUserIds(){
        $userNames = array();
        $query = Yii::app()->db->createCommand()
                ->select('id, CONCAT(firstName," ",lastName) AS name')
                ->from('x2_users')
                ->where('status=1')
                ->order('name ASC')
                ->query();

        while(($row = $query->read()) !== false)
            $userNames[$row['id']] = $row['name'];
        natcasesort($userNames);

        return array('' => Yii::t('app', 'Anyone')) + $userNames;
    }

    public function getName(){
        return $this->firstName.' '.$this->lastName;
    }

    public static function getProfiles(){
        $arr = X2Model::model('User')->findAll('status="1"');
        $names = array('0' => Yii::t('app', 'All'));
        foreach($arr as $user){
            $names[$user->id] = $user->firstName." ".$user->lastName;
        }
        return $names;
    }

    public static function getTopContacts(){
        $userRecord = X2Model::model('User')->findByPk(Yii::app()->user->getId());

        //get array of IDs
        $topContactIds = empty($userRecord->topContacts) ? array() : explode(',', $userRecord->topContacts);
        $topContacts = array();
        //get record for each ID
        foreach($topContactIds as $contactId){
            $record = X2Model::model('Contacts')->findByPk($contactId);
            if(!is_null($record)) //only include contact if the contact ID exists
                $topContacts[] = $record;
        }
        return $topContacts;
    }

    public static function getRecentItems(){
        $userRecord = X2Model::model('User')->findByPk(Yii::app()->user->getId());

        //get array of type-ID pairs
        $recentItemsTemp = empty($userRecord->recentItems) ? array() : explode(',', $userRecord->recentItems);
        $recentItems = array();

        //get record for each ID/type pair
        foreach($recentItemsTemp as $item){
            $itemType = strtok($item, '-');
            $itemId = strtok('-');

            switch($itemType){
                case 'c': // contact
                    $record = X2Model::model('Contacts')->findByPk($itemId);
                    break;
                case 't': // action
                    $record = X2Model::model('Actions')->findByPk($itemId);
                    break;
                case 'a': // account
                    $record = X2Model::model('Accounts')->findByPk($itemId);
                    break;
                case 'p': // campaign
                    $record = X2Model::model('Campaign')->findByPk($itemId);
                    break;
                case 'o': // opportunity
                    $record = X2Model::model('Opportunity')->findByPk($itemId);
                    break;
                case 'w': // workflow
                    $record = X2Model::model('Workflow')->findByPk($itemId);
                    break;
                case 's': // service case
                    $record = X2Model::model('Services')->findByPk($itemId);
                    break;
                case 'd': // document
                    $record = X2Model::model('Docs')->findByPk($itemId);
                    break;
                case 'm': // media object
                    $record = X2Model::model('Media')->findByPk($itemId);
                    break;
                case 'r': // product
                    $record = X2Model::model('Product')->findByPk($itemId);
                    break;
                case 'q': // product
                    $record = X2Model::model('Quote')->findByPk($itemId);
                    break;
                case 'g': // group
                    $record = X2Model::model('Groups')->findByPk($itemId);
                    break;
                default:
                    printR('Warning: getRecentItems: invalid item type');
                    continue;
            }
            if(!is_null($record)) //only include item if the record ID exists
                array_push($recentItems, array('type' => $itemType, 'model' => $record));
        }
        return $recentItems;
    }

    private static $validRecentItemTypes = array(
        'c', // contact
        't', // action
        'p', // campaign
        'o', // opportunity
        'w', // workflow
        's', // service case
        'd', // doc
        'm', // media object
        'r', // product
        'q', // quote
        'g', // group
        'a' // account
    );

    public static function addRecentItem($type, $itemId, $userId){
        if(in_array($type, self::$validRecentItemTypes)){ //only proceed if a valid type is given
            $newItem = $type.'-'.$itemId;

            $userRecord = X2Model::model('User')->findByPk($userId);
            //create an empty array if recentItems is empty
            $recentItems =
                    ($userRecord->recentItems == '') ? array() : explode(',', $userRecord->recentItems);
            $existingEntry = array_search($newItem, $recentItems); //check for a pre-existing entry
            if($existingEntry !== false)        //if there is one,
                unset($recentItems[$existingEntry]);    //remove it
            array_unshift($recentItems, $newItem);    //add new entry to beginning

            while(count($recentItems) > 10){ //now if there are more than 10 entries,
                array_pop($recentItems);  //remove the oldest ones
            }
            $userRecord->setAttribute('recentItems', implode(',', $recentItems));
            $userRecord->save();
        }
    }

    /**
     * Generate a link to a user or group.
     *
     * Creates a link or list of links to a user or group to be displayed on a record.
     * @param integer|array|string $users If array, links to a group; if integer, the user whose ID is that value; if keyword "Anyone", not a link but simply displays "anyone".
     * @param boolean $makeLinks Can be set to False to disable creating links but still return the name of the linked-to object
     * @return string The rendered links
     */
    public static function getUserLinks($users, $makeLinks = true, $useFullName = true){
        if(!is_array($users)){
            /* x2temp */
            if(is_numeric($users)){
                $group = Groups::model()->findByPk($users);
                if(isset($group))
                //$link = $makeLinks ? CHtml::link($group->name, array('/groups/groups/view', 'id' => $group->id)) : $group->name;
                    $link = $makeLinks ? CHtml::link($group->name, Yii::app()->controller->createAbsoluteUrl('/groups/groups/view', array('id' => $group->id))) : $group->name;
                else
                    $link = '';
                return $link;
            }
            /* end x2temp */
            if($users == '' || $users == 'Anyone')
                return Yii::t('app', 'Anyone');

            $users = explode(', ', $users);
        }
        $links = array();
        $userCache = Yii::app()->params->userCache;
        foreach($users as $user){
            if($user == 'Anyone' || $user == 'Email'){  // skip these, they aren't users
                continue;
            }else if(is_numeric($user)){  // this is a group
                if(isset($userCache[$user])){
                    $group = $userCache[$user];
                    //$links[] =  $makeLinks ? CHtml::link($group->name, array('/groups/groups/view', 'id' => $group->id)) : $group->name;
                    $links[] = $makeLinks ? CHtml::link($group->name, Yii::app()->controller->createAbsoluteUrl('/groups/groups/view', array('id' => $group->id))) : $group->name;
                }else{
                    $group = Groups::model()->findByPk($user);
                    // $group = Groups::model()->findByPk($users);
                    if(isset($group)){
                        //$groupLink = $makeLinks ? CHtml::link($group->name, array('/groups/groups/view', 'id' => $group->id)) : $group->name;
                        $groupLink = $makeLinks ? CHtml::link($group->name, Yii::app()->controller->createAbsoluteUrl('/groups/groups/view', array('id' => $group->id))) : $group->name;
                        $userCache[$user] = $group;
                        $links[] = $groupLink;
                    }
                }
            }else{
                if(isset($userCache[$user])){
                    $model = $userCache[$user];
                    $linkText = $useFullName ? $model->name : $user;
                    //$userLink = $makeLinks ? CHtml::link($linkText, array('/profile/view', 'id' => $model->id)) : $linkText;
                    $userLink = $makeLinks ? CHtml::link($linkText, Yii::app()->controller->createAbsoluteUrl('/profile/view', array('id' => $model->id))) : $linkText;
                    $links[] = $userLink;
                }else{
                    $model = X2Model::model('User')->findByAttributes(array('username' => $user));
                    if(isset($model)){
                        $linkText = $useFullName ? $model->name : $user;
                        //$userLink = $makeLinks ? CHtml::link($linkText, array('/profile/view', 'id' => $model->id)) : $linkText;
                        $userLink = $makeLinks ? CHtml::link($linkText, Yii::app()->controller->createAbsoluteUrl('/profile/view', array('id' => $model->id))) : $linkText;
                        $userCache[$user] = $model;
                        $links[] = $userLink;
                    }
                }
            }
        }
        Yii::app()->params->userCache = $userCache;
        return implode(', ', $links);
    }

    public static function getEmails(){
        $userArray = User::model()->findAllByAttributes(array('status' => 1));
        $emails = array('Anyone' => Yii::app()->params['adminEmail']);
        foreach($userArray as $user){
            $emails[$user->username] = $user->emailAddress;
        }
        return $emails;
    }

    /**
     * Returns the attribute labels.
     * @return array attribute labels (name=>label)
     */
    public function attributeLabels(){
        return array(
            'id' => Yii::t('users', 'ID'),
            'firstName' => Yii::t('users', 'First Name'),
            'lastName' => Yii::t('users', 'Last Name'),
            'username' => Yii::t('users', 'Username'),
            'password' => Yii::t('users', 'Password'),
            'title' => Yii::t('users', 'Title'),
            'department' => Yii::t('users', 'Department'),
            'officePhone' => Yii::t('users', 'Office Phone'),
            'cellPhone' => Yii::t('users', 'Cell Phone'),
            'homePhone' => Yii::t('users', 'Home Phone'),
            'address' => Yii::t('users', 'Address'),
            'backgroundInfo' => Yii::t('users', 'Background Info'),
            'emailAddress' => Yii::t('users', 'Email'),
            'status' => Yii::t('users', 'Status'),
            'updatePassword' => Yii::t('users', 'Update Password'),
            'lastUpdated' => Yii::t('users', 'Last Updated'),
            'updatedBy' => Yii::t('users', 'Updated By'),
            'recentItems' => Yii::t('users', 'Recent Items'),
            'topContacts' => Yii::t('users', 'Top Contacts'),
            'userKey' => Yii::t('users', 'API Key'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search(){

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('firstName', $this->firstName, true);
        $criteria->compare('lastName', $this->lastName, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('department', $this->department, true);
        $criteria->compare('officePhone', $this->officePhone, true);
        $criteria->compare('cellPhone', $this->cellPhone, true);
        $criteria->compare('homePhone', $this->homePhone, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('backgroundInfo', $this->backgroundInfo, true);
        $criteria->compare('emailAddress', $this->emailAddress, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('lastUpdated', $this->lastUpdated, true);
        $criteria->compare('updatedBy', $this->updatedBy, true);
        $criteria->compare('recentItems', $this->recentItems, true);
        $criteria->compare('topContacts', $this->topContacts, true);
        $criteria->compare('lastLogin', $this->lastLogin);
        $criteria->compare('login', $this->login);
        $criteria->addCondition('(temporary=0 OR temporary IS NULL)');

        return new SmartDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                    'pagination'=>array(
                        'pageSize'=>ProfileChild::getResultsPerPage(),
                    ),
                ));
    }

}
