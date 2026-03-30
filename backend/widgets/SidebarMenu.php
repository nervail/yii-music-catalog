<?php

namespace backend\widgets;

use yii\widgets\Menu as YiiMenu;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class SidebarMenu extends YiiMenu
{
    public $options = ['class' => 'nav nav-pills nav-sidebar flex-column', 'data-widget' => 'treeview', 'role' => 'menu', 'data-accordion' => 'false'];
    public $itemOptions = ['class' => 'nav-item'];
    public $linkTemplate = '<a href="{url}" class="nav-link {activeClass}"><i class="nav-icon {icon}"></i> <p>{label} {badge}</p></a>';
    public $submenuTemplate = "\n<ul class=\"nav nav-treeview\">\n{items}\n</ul>\n";
    public $activateParents = true;

    protected function renderItem($item)
    {
        $activeClass = $item['active'] ? 'active' : '';
        $icon = isset($item['icon']) ? $item['icon'] : 'far fa-circle';
        $badge = isset($item['badge']) ? $item['badge'] : '';

        if (!empty($item['items'])) {
            $label = $item['label'] . ' <i class="right fas fa-angle-left"></i>';
        } else {
            $label = $item['label'];
        }

        $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

        return strtr($template, [
            '{url}' => Html::encode(isset($item['url']) ? \yii\helpers\Url::to($item['url']) : '#'),
            '{label}' => $label,
            '{icon}' => $icon,
            '{activeClass}' => $activeClass,
            '{badge}' => $badge,
        ]);
    }
}