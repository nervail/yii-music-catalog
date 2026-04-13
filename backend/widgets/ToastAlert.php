<?php

namespace backend\widgets;

use Yii;
use yii\bootstrap5\Html;

class ToastAlert extends \yii\base\Widget
{
    /**
     * Соответствие типов Yii и классов Bootstrap/AdminLTE
     */
    public array $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning',
    ];

    /**
     * Соответствие иконок
     */
    public array $icons = [
        'danger'  => 'fa-ban',
        'success' => 'fa-check',
        'info'    => 'fa-info',
        'warning' => 'fa-exclamation-triangle',
    ];

    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $html = '';

        foreach ($flashes as $type => $data) {
            $alertClass = $this->alertTypes[$type] ?? 'alert-info';
            $baseClass = str_replace('alert-', '', $alertClass);
            $icon = $this->icons[$baseClass] ?? 'fa-info';

            foreach ((array) $data as $message) {
                $html .= Html::beginTag('div', [
                    'class' => "alert $alertClass alert-dismissible fade show shadow-lg",
                    'role' => 'alert',
                    'style' => 'min-width: 300px; margin-bottom: 10px;'
                ]);
                
                $html .= Html::button('×', [
                    'class' => 'close',
                    'data-dismiss' => 'alert',
                    'aria-label' => 'Close',
                    'aria-hidden' => "true"
                ]);

                $html .= Html::tag('h5', Html::tag('i', '', ['class' => "icon fas $icon"]) . ' ' . ucfirst($type) . '!');
                $html .= Html::encode($message);
                $html .= Html::endTag('div');
            }
        }

        if ($html) {
            $this->getView()->registerJs("
                const container = document.querySelector('#toast-container');
                if (container) {
                    const alerts = container.querySelectorAll('.alert');
                    alerts.forEach(function(alert) {
                        setTimeout(function() {
                            if (window.bootstrap && bootstrap.Alert) {
                                const bsAlert = new bootstrap.Alert(alert);
                                bsAlert.close();
                            } else {
                                alert.style.transition = 'opacity 0.6s ease';
                                alert.style.opacity = '0';
                                setTimeout(() => alert.remove(), 600);
                            }
                        }, 5000);
                    });
                }
            ");
            
            return $html ? Html::tag('div', $html, [
                'id' => 'toast-container',
                'style' => 'position: fixed; top: 20px; right: 20px; z-index: 9999;'
            ]) : '';
        }
    }
}