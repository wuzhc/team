<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fdStatus', 'fdRoleID'], 'integer'],
            [['fdName', 'fdLogin', 'fdPhone', 'fdEmail', 'fdPortrait', 'fdCreate', 'fdVerify', 'fdLastIP', 'fdLastTime', 'fdPwdHash', 'fdPwdResetToken', 'fdAuthKey'], 'safe'],
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
        $query = User::find();

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
            'fdStatus' => $this->fdStatus,
            'fdRoleID' => $this->fdRoleID,
            'fdCreate' => $this->fdCreate,
            'fdVerify' => $this->fdVerify,
            'fdLastTime' => $this->fdLastTime,
        ]);

        $query->andFilterWhere(['like', 'fdName', $this->fdName])
            ->andFilterWhere(['like', 'fdLogin', $this->fdLogin])
            ->andFilterWhere(['like', 'fdPhone', $this->fdPhone])
            ->andFilterWhere(['like', 'fdEmail', $this->fdEmail])
            ->andFilterWhere(['like', 'fdPortrait', $this->fdPortrait])
            ->andFilterWhere(['like', 'fdLastIP', $this->fdLastIP])
            ->andFilterWhere(['like', 'fdPwdHash', $this->fdPwdHash])
            ->andFilterWhere(['like', 'fdPwdResetToken', $this->fdPwdResetToken])
            ->andFilterWhere(['like', 'fdAuthKey', $this->fdAuthKey]);

        return $dataProvider;
    }
}
