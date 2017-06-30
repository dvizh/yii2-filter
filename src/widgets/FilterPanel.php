<?php
namespace dvizh\filter\widgets;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dvizh\filter\models\Filter;
use dvizh\filter\models\FieldRelationValue;
use yii2mod\slider\IonSlider;
use yii;

class FilterPanel extends \yii\base\Widget
{
    public $itemId = NULL;
    public $filterId = NULL;
    public $itemCssClass = 'item';
    public $fieldName = 'filter';
    public $blockCssClass = 'block';
    public $findModel = false; //::find() модели, по которой будем искать соответствия
    public $ajaxLoad = false; //Ajax подгрузка результатов
    public $resultHtmlSelector = null; //CSS селектор, который хранит результаты
    public $submitButtonValue = 'Показать';
    public $actionRoute = false;
    public $filterGetParamName = 'filter';
    
    public function init()
    {
        parent::init();

        if($this->ajaxLoad) {
            \dvizh\filter\assets\FrontendAjaxAsset::register($this->getView());
        } else {
            \dvizh\filter\assets\FrontendAsset::register($this->getView());
        }
    }

    public function run()
    {
        $params = ['is_filter' => 'yes'];

        if($this->filterId) {
            $params['id'] = $this->filterId;
        }

        $filters = Filter::find()->orderBy('sort DESC')->andWhere($params)->all();

        $return = [];
        foreach($filters as $filter) {
            if(empty($this->itemId) || in_array($this->itemId, $filter->selected)) {
                $block = '';
                $title = Html::tag('p', $filter->name, ['class' => 'heading']);
                
                if($this->findModel) {
                    $variants = $filter->getVariantsByFindModel($this->findModel)->all();
                } else {
                    $variants = $filter->variants;
                }

                if($filter->type == 'range') {
                    $max = 0;
                    $min = 0;
                    foreach($variants as $variant) {
                        if($max < $variant->numeric_value) {
                            $max = $variant->numeric_value;
                        }
                        if($min > $variant->numeric_value) {
                            $min = $variant->numeric_value;
                        }
                    }
                    
                    $fieldName = $this->fieldName.'['.$filter->id.']';
  
                    $from = $min;
                    $to = $max;
                    
                    $value = yii::$app->request->get($this->fieldName)[$filter->id];
                    
                    if($value) {
                        $values = explode(';', $value);
                        $from = $values[0];
                        $to = $values[1];
                    }
                    
                    if(!empty($variants)) {
                        $step = round($max/count($variants));
                    } else {
                        $step = 1;
                    }

                    $block = IonSlider::widget([
                        'name' => $fieldName,
                        'value' => $value,
                        'type' => "double",
                        'pluginOptions' => [
                            'drag_interval' => true,
                            'grid' => true,
                            'min' => $min,
                            'max' => $max,
                            'from' => $from,
                            'to' => $to,
                            'step' => $step,
                        ]
                    ]);
                } elseif($filter->type == 'select') {
                    $fieldName = $this->fieldName.'['.$filter->id.']';
                    
                    $value = yii::$app->request->get($this->fieldName)[$filter->id];
                    
                    $variantsListWithNull = ['' => '-'];
                    
                    $variantsList = ArrayHelper::map($variants, 'id', 'value');
                    
                    foreach($variantsList as $id => $value) {
                        $variantsListWithNull[$id] = $value;
                    }
                    
                    $block = Html::dropDownList($fieldName, $value, $variantsListWithNull, ['class' => 'form-control']);
                } else {
                    foreach($variants as $variant) {
                        $checked = false;
                        
                        if($filterData = yii::$app->request->get($this->filterGetParamName)) {
                            if($this->findModel) {
                                $filterParams = $this->findModel->convertFilterUrl($filterData);
                            } else {
                                $filterParams = $filterData;
                            }
                            if(isset($filterParams[$filter->id]) && (isset($filterParams[$filter->id][$variant->id]) |  $filterParams[$filter->id] == $variant->id)) {
                                $checked = true;
                            }
                        }

                        if(!in_array($filter->type, array('radio', 'checkbox', 'range'))) {
                            $filter->type = 'checkbox';
                        }

                        if($filter->type == 'radio') {
                            $fieldName = $this->fieldName.'['.$filter->id.']';
                        } else {
                            $fieldName = $this->fieldName.'['.$filter->id.']['.$variant->id.']';
                        }

                        $field = Html::input($filter->type, $fieldName, $variant->id, ['checked' => $checked, 'data-item-css-class' => $this->itemCssClass, 'id' => "variant{$variant->id}"]);

                        if($this->actionRoute) {
                            $field .= Html::label(Html::a($variant->value, $this->buildUrl($filter->slug, $variant->latin_value, $filter->type)), "variant{$variant->id}"); 
                        } else {
                            $field .= Html::label($variant->value, "variant{$variant->id}"); 
                        }
                        
                        $block .= Html::tag('div', $field);
                    }
                }
                
                if(!empty($variants)) {
                    $return[] = Html::tag('div', $title.$block, ['class' => $this->blockCssClass]);
                }
            }
        }

        if($return) {
            $return[] = Html::input('submit', '', $this->submitButtonValue, ['class' => 'btn btn-submit']);

            foreach(yii::$app->request->get() as $key => $value) {
                if(!is_array($value)) {
                    $return[] = Html::input('hidden', Html::encode($key), Html::encode($value));
                }
            }
            
            $action = $this->actionRoute;
            
            if($action) {
                $action = Url::toRoute($action);
            }

            return Html::tag('form', implode('', $return), ['data-resulthtmlselector' => $this->resultHtmlSelector, 'name' => 'dvizh-filter', 'action' => $action, 'class' => 'dvizh-filter']);
        }
        
        return null;
    }
    
    public function buildUrl($filterSlug, $variantValue, $filterType = 'radio')
    {
        if(!is_array($this->actionRoute) | is_array(yii::$app->request->get($this->filterGetParamName))) {
            return '#';
        }
        
        if($params = yii::$app->request->get($this->filterGetParamName)) {
            $filterString = explode('_and_', $params);
        } else {
            $filterString = [];
        }
        
        $params = [];
        
        //decompose
        foreach($filterString as $filterData) {
            $filterData = explode('_is_', $filterData);
            $params[$filterData[0]] = explode('_or_', $filterData[1]);
        }
        
        if(!isset($params[$filterSlug])) {
            $params[$filterSlug] = [];
        }
        
        if($filterType == 'checkbox') {
            if(!in_array($variantValue, $params[$filterSlug])) {
                $params[$filterSlug][] = $variantValue;
            }
        } else {
            $params[$filterSlug] = [$variantValue];
        }
        
        
        //compose
        $filterString = [];
        foreach($params as $filterSlug => $filterVariants) {
            $filterString[] = $filterSlug . '_is_' . implode('_or_', $filterVariants);
        }
        
        $this->actionRoute[$this->filterGetParamName] = implode('_and_', $filterString);
        
        return Url::toRoute($this->actionRoute);
    }
}
