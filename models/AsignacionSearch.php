<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TareasSearch represents the model behind the search form about `app\models\Tareas`.
 */
class AsignacionSearch extends Asignacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idasignartarea',  'idtarea', 'idtareaidusuario', ], 'integer'],
            [['descripcion', 'fechainicio', 'fechafin', 'estado'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Asignacion::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idasignartarea' => $this->idasignartarea,
            'idtarea' => $this->idtarea,
        ]);

        $query->andFilterWhere(['like', 'fechainicio', $this->fechainicio])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
