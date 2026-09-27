<?php

    /* ========================= */
    /* init */
    /* ========================= */
    include '../env/env.php';
    require_once ROOT. '/vendor/autoload.php';

    use Google\Auth\Credentials\ServiceAccountCredentials;

    /* BO */
    // GGnavi::getPer10ApiInsertPaymentDepositedByList();

    /* vars */
    $rslt = Common::getReturn();

    /* BO */
    // $batchBO = Per10ApiInsertPaymentDepositedByList::getInstance();

    /* ========================= */
    /* process */
    /* ========================= */
    try
    {
        /*
        * Firebase 프로젝트 ID
        *
        * Firebase Console
        * → 프로젝트 설정
        * → 일반
        * → 프로젝트 ID
        */
        $projectId = 'circle-is-baseball';

        /*
        * 서비스 계정 JSON
        *
        * 웹에서 접근할 수 없는 위치에 보관하는 것을 권장
        */
        $serviceAccountFile = FCM_KEY;

        /*
        * 테스트할 FCM Registration Token
        *
        * Cordova 앱에서 getToken()으로 받은 값
        */
        $fcmToken = 'c354GR5KRJO2mjSDq60qC7:APA91bEC4iMm0bhSmshwtQyVi6YrhcSdXb-X-g676OM1qJ9FauGVqz0-Mf0kRQde9ilzvv0iNqL0geGCKAKdA67P-78gIbkZuN3-AAFY1bAWTcEd7dkooZA';


        /**
         * OAuth 2.0 Access Token 생성
         */
        $credentials = new ServiceAccountCredentials(
            'https://www.googleapis.com/auth/firebase.messaging',
            json_decode(
                file_get_contents($serviceAccountFile),
                true
            )
        );
        $accessToken = $credentials->fetchAuthToken()['access_token'];

        /**
         * FCM 메시지
         */
        $message = [
            'message' => [
                'token' => $fcmToken,

                'notification' => [
                    'title' => '모임은야구',
                    'body'  => 'PHP에서 보낸 FCM 테스트 메시지입니다.'
                ],

                'data' => [
                    'type' => 'test',
                    'message_id' => '1234'
                ]
            ]
        ];


        /**
         * FCM HTTP v1 API
         */
        $url = 'https://fcm.googleapis.com/v1/projects/'.$projectId.'/messages:send';
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json; UTF-8'
            ],
            CURLOPT_POSTFIELDS => json_encode($message),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        /**
         * 결과 확인
         */
        if ($response === false)
        {
            echo "CURL ERROR\n";
            echo $curlError . "\n";
            exit;
        }
        echo "HTTP CODE : " . $httpCode . "\n";
        echo "RESPONSE : " . $response . "\n";
    }
    catch(GGexception $e)
    {
        $rslt = Common::returnError($e->getMessage(), $e);
    }
    catch(Error $e)
    {
        $rslt = Common::returnErrorObj($e);
    }
    Common::returnRslt($rslt);

?>
