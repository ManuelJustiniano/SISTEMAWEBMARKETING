<?php

namespace app\models;

/**
 * This is the model class for table "categoria".
 *
 * @property integer $idcategoria
 * @property string $nombre
 * @property string $descripcion
 * @property string $imagen
 * @property integer $idpadre
 * @property integer $modulo
 * @property string $estado
 */
class Tareas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tareas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['titulo', 'descripcion'], 'required'],
            [['descripcion'], 'string'],
            [['modulo'], 'integer'],
            [['titulo'], 'string', 'max' => 250],
            [['estado'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idtarea' => 'ID',
            'titulo' => 'Nombre de la tarea',
            'descripcion' => 'Descripcion especifica de la tarea',
            'modulo' => 'Modulo a asignar',
            'estado' => 'Estado',
        ];
    }


    public static function getSelectTareas()
    {
        $result =[];
        $temp = Tareas::find()->where([])->all();
        foreach ($temp as $item) {
            $result[$item->idtarea]="".$item->titulo;
        }
        return $result;
    }

    public function getCategoryMenu($limit = 0, $offset = 7)
    {
        $data = Tareas::find()->where(['estado' => 1, 'idpadre' => 0, 'modulo' => 29])->orderBy(['(idcategoria)' => SORT_ASC])->limit($offset, $limit)->all();
        return $data;
    }

    public function getCategoryBy($idpadre = 0)
    {
        $data = Tareas::find()->where('idpadre=:idpadre AND estado = 1 AND modulo = 29', ['idpadre' => $idpadre])->orderBy(['(idcategoria)' => SORT_ASC])->all();
        return $data;
    }

    /*
     * seccion de relaciones entre tablas
     */
    public function getCategorias()
    {
        return $this->hasMany(Tareas::className(), ['idpadre' => 'idcategoria']);
    }

    public function getPadre()
    {
        return $this->hasOne(Tareas::className(), ['idcategoria' => 'idpadre']);
    }

    public function getModulos()
    {
        return $this->hasOne(Modulos::className(), ['idmodulo' => 'modulo']);
    }

    /*
     * funcion para sacar menu select2
     */
    public static function getSelectMenu($modulo)
    {
        $cat = Tareas::find()->where(['=', 'modulo', Modulos::findOne(['alias' => $modulo])['idmodulo']])->all();
        $result = [];
        $temp = [];
        foreach ($cat as $item) {
            if (empty($item->idpadre)) {
                array_push($temp, $item);
            }
        }
        foreach ($temp as $key => $item) {
            $temps = Tareas::find()->where(['=', 'idpadre', $item->idcategoria])->all();


            if (count($temps) > 0) {
                foreach ($temps as $value) {
                    $temps2 = Tareas::find()->where(['=', 'idpadre', $value->idcategoria])->all();
                    if (count($temps2) > 0) {
                        foreach ($temps2 as $value2) {
                            $result["<span class='fa fa-square'></span> " . $item->nombre]["<span class='fa fa-caret-right'></span> " . $value->nombre][$value2->idcategoria] = "<span class='fa fa-angle-double-right'></span> " . $value2->nombre;
                        }
                    } else {
                        $result["<span class='fa fa-square'></span> " . $item->nombre][$value->idcategoria] = "<span class='fa fa-caret-right'></span> " . $value->nombre;
                    }
                }
            } else {
                $result[$item->idcategoria] = "<span class='fa fa-square'></span> " . $item->nombre;
            }
        }
        return $result;

    }

    /*
     * funcion para obtener categorias de primer nivel por alias
     */
    static public function getMenu($modulo, $limit = 0)
    {
        $data = Tareas::find()
            ->where(['modulo' => Modulos::findOne(['alias' => $modulo])['idmodulo'], 'estado' => '1'])
            ->orderBy(['idcategoria' => SORT_ASC]);
        if ($limit > 0)
            $data->limit($limit);
        return $data->all();
    }
}
