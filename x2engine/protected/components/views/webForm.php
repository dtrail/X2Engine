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

/*
Parameters:
    type - string, the web form type ('weblead' | 'weblist' | 'service')
    model - the model associated with the form, set to Contacts by default
*/



mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');
Yii::app()->params->profile = ProfileChild::model()->findByPk(1);
if (empty($type)) $type = 'weblead';
if (empty($model)) $model = Contacts::model ();



if ($type === 'service') {
    $modelName = 'Services';
} else if ($type === 'weblead')  {
    $modelName = 'Contacts';
}


$defaultFields;
if ($type === 'weblead') {
    $defaultFields = array (
        array (
            'fieldName' => 'firstName',
            'position' => 'top',
            'required' => 1
        ),
        array (
            'fieldName' => 'lastName',
            'position' => 'top',
            'required' => 1
        ),
        array (
            'fieldName' => 'email',
            'position' => 'top',
            'required' => 1
        ),
        array (
            'fieldName' => 'phone',
            'position' => 'top',
            'required' => 0
        ),
        array (
            'fieldName' => 'backgroundInfo',
            'position' => 'top',
            'required' => 0
        ),
    );
} else if ($type === 'service') {
    $defaultFields = array (
        array (
            'fieldName' => 'firstName',
            'position' => 'top',
            'required' => 1
        ),
        array (
            'fieldName' => 'lastName',
            'position' => 'top',
            'required' => 1
        ),
        array (
            'fieldName' => 'email',
            'position' => 'top',
            'required' => 1
        ),
        array (
            'fieldName' => 'phone',
            'position' => 'top',
            'required' => 0
        ),
        array (
            'fieldName' => 'description',
            'position' => 'top',
            'required' => 0
        ),
    );
}

$useDefaults = false;


    $fieldList = $defaultFields;
    $useDefaults = true;


$fieldTypes = array_map (function ($elem) { if ($elem['required']) return $elem['fieldName']; }, $fieldList);

if ($type === 'service') {
    $contactFields = array('firstName', 'lastName', 'email', 'phone');
} else {
    $contactFields = null;
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
 xml:lang="<?php echo Yii::app()->language; ?>" lang="<?php echo Yii::app()->language; ?>">
<head>
<meta charset="UTF-8" />
<meta name="language" content="<?php echo Yii::app()->language; ?>" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<?php $this->renderGaCode('public'); ?>

<script type="text/javascript"
 src="<?php echo Yii::app()->clientScript->coreScriptUrl . '/jquery.js'; ?>">
</script>

<?php

?>

<style type="text/css">
html {
    <?php
    /* Dear future editors:
      The pixel height of the iframe containing this page
      should equal the sum of height, padding-bottom, and 2x border size
      specified in this block, else the bottom border will not be at the
      bottom edge of the frame. Now it is based on 325px height for weblead,
      and 100px for weblist */

    if (!empty($_GET['iframeHeight'])) {
        $height = $_GET['iframeHeight'];
        unset($_GET['iframeHeight']);
    } else {
        $height = $type == 'weblist' ? 125 : 325;
    }
    if (!empty($_GET['bs'])) {
        $border = intval(preg_replace('/[^0-9]/', '', $_GET['bs']));
    } else if (!empty($_GET['bc'])) {
        $border = 1;
    } else $border = 0;
    $padding = 36;
    $height = $height - $padding - (2 * $border);

    echo 'border: '. $border .'px solid ';
    if (!empty($_GET['bc'])) echo $_GET['bc'];
    echo ";\n";

    unset($_GET['bs']);
    unset($_GET['bc']);
    ?>

    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    padding-bottom: <?php echo $padding ."px;\n"?>
    height: <?php echo $height ."px;\n"?>
}
body {
    <?php
    if (!empty($_GET['fg'])) {
        echo 'color: '. $_GET['fg'] .";\n";
    }
    unset($_GET['fg']);
    if (!empty($_GET['bgc'])) echo 'background-color: '. $_GET['bgc'] .";\n";
    unset($_GET['bgc']);
    if (!empty($_GET['font'])) {
        echo 'font-family: '. FontPickerInput::getFontCss($_GET['font']) .";\n";
    } else {
        echo "font-family: Arial, Helvetica, sans-serif;\n";
    }
    unset($_GET['font']);
    ?>
    font-size:12px;
}
input {
    border: 1px solid #AAA;
}
textarea {
    width: 166px;
    height: 100px;
}
input[type="text"] {
    width: 170px;
}
#contact-header{
    color:white;
    text-align:center;
    font-size: 16px;
}
#submit {
    float: right;
    margin-top: 7px;
    margin-bottom: 5px;
    margin-right: 0px;
}
.submit-button-row {
    width: 172px;
    height: 30px;
}
<?php

?>
</style>


<?php

?>
</head>
<body>

<?php
foreach(Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
}

$form = $this->beginWidget('CActiveForm', array(
    'id'=>$type,
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array(
        'onSubmit'=> (($useDefaults) ? 'return validate();' : ''),
    ),
));

function renderFields ($fieldList, $type, $form, $model, $contactFields=null) {
    foreach($fieldList as $field) {
        if(!isset($field['type']) || $field['type']!='hidden'){
            if(isset($field['label']) && $field['label'] != '') {
                $label = '<label>' . $field['label'] . '</label>';
            } else {
                if($type === 'service' && in_array($field['fieldName'], $contactFields)){
                    $label = Contacts::model()->getAttributeLabel($field['fieldName']);
                }else{
                    $label = $form->labelEx($model,$field['fieldName']);
                }
            }

            // label already has a '*' to indicate it is required
            $starred = strpos($label, '*') !== false;
            ?>
            <div class="row">
                <b>
                    <?php
                    echo $label;
                    echo ($field['required'] && !$starred ? '*' : '');
                    ?>
                </b>
                <?php
            if($field['position'] == 'top') { ?>
                <br />
            <?php
            }
            echo $form->error($model, $field['fieldName']);

            if($type === 'service' && in_array($field['fieldName'], $contactFields)){ ?>
                <input type="text" name="Services[<?php echo $field['fieldName']; ?>]"
                value="<?php echo isset($_POST['Services'][$field['fieldName']]) ?
                $_POST['Services'][$field['fieldName']] : ''; ?>" />
            <?php
            } else {
                
                    echo $model->renderInput($field['fieldName']);
                
            } ?>
            </div>
<?php
        }else{
            $model->{$field['fieldName']}=$field['label'];
            echo $form->hiddenField($model, $field['fieldName']);
        }
    }
}

renderFields ($fieldList, $type, $form, $model, $contactFields);

// renders hidden tracking key field
foreach ($_GET as $key=>$value) { ?>
    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
    <?php
}



?>
<div class="submit-button-row row">
<?php
echo CHtml::submitButton(Yii::t('app','Submit'),array('id'=>'submit'));
?>
</div>

<?php
$this->endWidget();
?>

<script>

var defaultFields = <?php echo CJSON::encode ($fieldTypes); ?>;

/*
Sets input to empty string unless it contains the default value
*/
function clearText(field){
    if (typeof field !== "undefined" && $(field).prop ('defaultValue') === $(field).val ())
        $(field).val ("");
}

/*
Add error styling if field input is invalid.
Returns: false if field input is invalid, true otherwise
*/
function validateField(field) {
    var input = $('#<?php echo $type; ?>').find (
        '[name="<?php echo $modelName; ?>[' + field + ']"]');

    if (!$(input).val () || // field is empty
        $(input).val ().match (/^\s+$/) || // field contains only whitespace
        (field == "email" && // invalid email format
         $(input).val ().match(/[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/) == null)) {

        // add error styling
        $(input).css ({"border-color": "#c00"});
        $(input).css ({"background-color": "#fee"});
        return false;
    } else {

        // remove error styling
        $(input).css ({"border-color": ""});
        $(input).css ({"background-color": ""});
        return true;
    }
}

/*
Clear and validate all default input fields
*/
function validate() {

    clearText ($('#<?php echo $type; ?>').find (
        '[name="<?php echo $modelName; ?>[backgroundInfo]"]'));

    var valid = true;
    for (var i in defaultFields) {
        if (defaultFields[i] === null) continue;
        if (!validateField(defaultFields[i])) {
            valid = false;
        }
    }
    return valid;
}

<?php

?>
</script>

</body>
</html>
