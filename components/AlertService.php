<?php
namespace app\components;

use app\models\Usuarios;
use Yii;

class AlertService implements InterfaceAlert
{

    public function agregarMensajeExito($mensaje)
    {
        Yii::$app->session->setFlash('success', ['message' => $mensaje, 'type' => 'success']);
    }

    public function agregarMensajeError($mensaje)
    {
        Yii::$app->session->setFlash('error', ['message' => $mensaje]);
    }

}