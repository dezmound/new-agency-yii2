<?php

namespace app\controllers;

use app\models\Token;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `reporter` module
 */
class TokensController extends ActiveController
{
    public $modelClass = 'app\models\Token';

    public function actions(){
        return @ArrayHelper::remove(parent::actions(), 'create');
    }

    public function actionGenerate(){
        $token = new Token();
        if($token->load(\Yii::$app->request->get(), '') && $token->validate()){
            $token->token = (string)(new Builder())
                ->setIssuer(\Yii::$app->name)
                ->setIssuedAt(time())
                ->setNotBefore(time())
                ->setExpiration(time() + Token::TIME_EXPIRED)
                ->set('aid', $token->auth_id)
                ->sign((new Sha256()), \Yii::$app->service['secretKey'])
                ->getToken();
            $token->save();

            return $token;
        }
        throw new BadRequestHttpException('Неверные данные для генерации токена');
    }

    public function actionValidate(){
        $token = new Token();
        if($token->load(\Yii::$app->request->get(), '') && $token->validate()){
            $token->token = (new Parser())->parse($token->token);
            if($token->token && $token->token->verify((new Sha256()), \Yii::$app->service['secretKey'])){
                $validator = new ValidationData();
                $validator->setCurrentTime(time());
                if($token->token->validate($validator)){
                    return Token::findOne(['token' => (string)$token->token]);
                }
                throw new NotFoundHttpException('Время жизни токена истекло');
            }
        }
        throw new NotFoundHttpException('Неверный токен пользователя');
    }
}
