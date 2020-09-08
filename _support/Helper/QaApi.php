<?php
namespace Helper;
use Codeception\Module\REST;

class QaApi extends REST
{
    /**
     * Получение смс-кода подтверждения через QApi.
     *
     * @param  string $token Токен, поулченный со страницы запроса смс-кода
     * @return mixed Смс-код
     * @throws \Exception
     */
    public function getConfirmSmsCode($token)
    {
        $I = $this;

        $I->sendPOST('service/sms/confirmation/?accessKey=5aeGpaJjnnbZ', ["token" => $token]);
        $resultCode = $I->grabDataFromResponseByJsonPath('result.code')[0];
        codecept_debug($resultCode);

        return $resultCode;
    }

    /**
     * Отключение проверки капчи.
     *
     * @return mixed
     * @throws \Exception
     */
    public function disableCaptcha()
    {
        $I = $this;

        $I->sendPOST('service/captcha/disable/?accessKey=5aeGpaJjnnbZ');
        codecept_debug($I->grabResponse());
        $resultToken = $I->grabDataFromResponseByJsonPath('result.token')[0];
        codecept_debug('result token is ' . $resultToken);

        return $resultToken;
    }

    /**
     * Удаление тестового пользователя.
     *
     * @param string $userId Идентификатор пользователя
     * @return mixed Статус операции
     * @throws \Exception
     */
    public function deleteUserById($userId)
    {
        $I = $this;

        $I->sendPOST("user/delete/", ['accessKey' => '5aeGpaJjnnbZ', 'user_id' => $userId]);
        $resultCode = $this->grabDataFromResponseByJsonPath('result.isUserDeleted')[0];

        return $resultCode;
    }
}
