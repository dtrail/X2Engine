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


class CreateWebFormAction extends CAction {

    /**
     * Create a web lead form with a custom style
     *
     * Currently web forms have all options passed as GET parameters. Saved web forms
     * are saved to the table x2_web_forms. Saving, retrieving, and updating a web form
     * all happens in this function. Someday this should be updated to be it's own module.
     *
     * 
     * This get request is for weblead/service type only, marketing/weblist/view supplies 
     * the form that posts for weblist type 
     *  
     */
    public function run(){
        $modelClass = $this->controller->modelClass;
        if ($modelClass === 'Campaign') $modelClass = 'Contacts';

        if($_SERVER['REQUEST_METHOD'] === 'POST'){ // save a web form
            if(empty($_POST['name'])){
                if ($modelClass === 'Contacts') {
                    echo json_encode(array(
                        'errors' => array(
                            'name' => Yii::t('marketing', 'Name cannot be blank.'))));
                } elseif ($modelClass === 'Services') {
                    echo json_encode(array(
                        'errors' => array(
                            'name' => Yii::t('marketing', 'Name cannot be blank.'))));
                }
                return;
            }

            if ($modelClass === 'Contacts')
                $type = !empty($_POST['type']) ? $_POST['type'] : 'weblead';
            elseif ($modelClass === 'Services')
                $type = 'serviceCase';
            
            $model = WebForm::model()->findByAttributes(
                array('name' => $_POST['name'], 'type' => $type));

            // check if we are updating an existing web form
            if(!isset($model)){
                $model = new WebForm;
                $model->name = $_POST['name'];
                $model->type = $type;
                $model->modelName = $modelClass;
                $model->visibility = 1;
                $model->assignedTo = Yii::app()->user->getName();
                $model->createdBy = Yii::app()->user->getName();
                $model->createDate = time();
            }

            //grab web lead configuration and stash in 'params'
            $whitelist = array('fg', 'bgc', 'font', 'bs', 'bc', 'tags');
            $config = array_filter(array_intersect_key($_POST, array_flip($whitelist)));
            //restrict param values, alphanumeric, # for color vals, comma for tag list
            $config = preg_replace('/[^a-zA-Z0-9#,]/', '', $config);
            if(!empty($config))
                $model->params = $config;
            else
                $model->params = null;

            

            $model->updatedBy = Yii::app()->user->getName();
            $model->lastUpdated = time();

            if($model->save()){
                echo json_encode($model->attributes);
            }else{
                echo json_encode(array('errors' => $model->getErrors()));
            }
        }else{
            if ($modelClass === 'Contacts') {
                $condition = X2Model::model('Marketing')->getAccessCriteria()->condition;

                $forms = WebForm::model()->findAll('type="weblead" AND '.$condition);

                $this->controller->render(
                    'application.modules.marketing.views.marketing.webleadForm', 
                    array('forms' => $forms));
            } else if ($modelClass === 'Services') {
                $condition = X2Model::model('Services')->getAccessCriteria()->condition;

                // get service web forms (other option is 'weblead' used by marketing module)
                $forms = WebForm::model()->findAll('type="serviceCase" AND '.$condition); 

                $this->controller->render(
                    'application.modules.services.views.services.createWebFormView', 
                    array('forms' => $forms));
            }

        }
    }

}

?>
