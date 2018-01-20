<?php

namespace common\models;

use common\config\Conf;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Project;

/**
 * ProjectSearch represents the model behind the search form of `\common\models\Project`.
 */
class ProjectSearch extends Project
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fdCreatorID', 'fdCompanyID', 'fdStatus'], 'integer'],
            [['fdName', 'fdDescription', 'fdCreate', 'fdUpdate'], 'safe'],
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
        $query = Project::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'fdCreatorID' => $this->fdCreatorID,
            'fdCompanyID' => Yii::$app->user->identity->fdCompanyID,
            'fdStatus' => Conf::ENABLE,
            'fdCreate' => $this->fdCreate,
            'fdUpdate' => $this->fdUpdate,
        ]);

        $query->andFilterWhere(['like', 'fdName', $this->fdName])
            ->andFilterWhere(['like', 'fdDescription', $this->fdDescription]);

        return $dataProvider;
    }
}
