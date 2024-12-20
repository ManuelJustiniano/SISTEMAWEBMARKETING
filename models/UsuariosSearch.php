<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UsuariosSearch represents the model behind the search form about `app\models\Usuarios`.
 */
class UsuariosSearch extends Usuarios
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idusuario', 'tipo_usuario'], 'integer'],
            [['nombrecompleto', 'direccion', 'telefono', 'pais', 'ciudad', 'email', 'contrasena', 'fecha_nacimiento', 'movil', 'fecha_registro', 'estado', 'sexo', 'cargo', 'usuario', 'empresa'], 'safe'],
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
        $query = Usuarios::find();

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
            'idusuario' => $this->idusuario,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'nombrecompleto' => $this->nombrecompleto,
            'tipo_usuario' => $this->tipo_usuario,
        ]);

        $query->andFilterWhere(['like', 'nombrecompleto', $this->nombrecompleto])
            ->andFilterWhere(['like', 'usuario', $this->usuario])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'pais', $this->pais])
            ->andFilterWhere(['like', 'ciudad', $this->ciudad])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'contrasena', $this->contrasena])
            ->andFilterWhere(['like', 'movil', $this->movil])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'sexo', $this->sexo])
            ->andFilterWhere(['like', 'tipo_usuario', $this->tipo_usuario])
            ->andFilterWhere(['like', 'empresa', $this->empresa]);

        return $dataProvider;
    }
}
