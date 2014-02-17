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
 * X2FlowAction that calls a remote API
 *
 * @package X2CRM.components.x2flow.actions
 */
class X2FlowApiCall extends X2FlowAction {

    public $title = 'Remote API Call';
    public $info = 'Call a remote API by requesting the specified URL. You can specify the request type and any variables to be passed with the request. To improve performance, the request will be put into a job queue unless you need it to execute immediately.';

    public function paramRules(){
        $httpVerbs = array(
            'GET' => Yii::t('studio', 'GET'),
            'POST' => Yii::t('studio', 'POST'),
            'PUT' => Yii::t('studio', 'PUT'),
            'DELETE' => Yii::t('studio', 'DELETE')
        );

        return array(
            'title' => Yii::t('studio', $this->title),
            'info' => Yii::t('studio', $this->info),
            'modelClass' => 'API_params',
            'options' => array(
                array('name' => 'url', 'label' => Yii::t('studio', 'URL')),
                array('name' => 'method', 'label' => Yii::t('studio', 'Method'), 'type' => 'dropdown', 'options' => $httpVerbs),
                array('name' => 'attributes', 'optional' => 1),
            // array('name'=>'immediate','label'=>'Call immediately?','type'=>'boolean','defaultVal'=>true),
                ));
    }

    public function execute(&$params){
        $url = $this->parseOption('url', $params);
        if(strpos($url,'http')===false){
            $url = 'http://'.$url;
        }
        $method = $this->parseOption('method', $params);

        if($this->parseOption('immediate', $params) || true){
            $headers = array();
            if(isset($this->config['attributes']) && !empty($this->config['attributes'])){
                $httpOptions = array(
                    'timeout' => 5, // 5 second timeout
                    'method' => $method,
                    'header' => implode("\r\n", $headers),
                );
                $data=array();
                foreach($this->config['attributes'] as $param){
                    if(isset($param['name'],$param['value'])){
                        $data[$param['name']]=$param['value'];
                    }
                }
                $data = http_build_query($data);
                if($method === 'GET'){
                    $url .= strpos($url, '?') === false ? '?' : '&'; // make sure the URL is ready for GET params
                    $url .= $data;
                }else{
                    $headers[] = 'Content-type: application/x-www-form-urlencoded'; // set up headers for POST style data
                    $headers[] = 'Content-Length: '.strlen($data);
                    $httpOptions['content'] = $data;
                    $httpOptions['header'] = implode("\r\n", $headers);
                }
            }
            $context = stream_context_create(array('http' => $httpOptions));
            if(file_get_contents($url, false, $context)!==false){
                return array(true, "");
            }else{
                return array(false, "");
            }
        }
    }

}
