<?php

    /* ========================= */
    /* init */
    /* ========================= */
    include '../env/env.php';
    require_once ROOT. '/vendor/autoload.php';

    use Kreait\Firebase\Factory;
    use Kreait\Firebase\Messaging\CloudMessage;
    use Kreait\Firebase\Messaging\Notification;
    // use Google\Auth\Credentials\ServiceAccountCredentials;

    /* filename */
    $filename = 'batch-sec01-sendFcmMsg';

    /* BO */
    GGnavi::getSystemBatchBO();
    GGnavi::getSystemPushBO();
    GGnavi::getGrpMemberBO();
    GGnavi::getUserBO();
    GGnavi::getClsBO();
    $systemBatchBO = SystemBatchBO::getInstance();
    $systemPushBO = SystemPushBO::getInstance();
    $grpMemberBO = GrpMemberBO::getInstance();
    $userBO = UserBO::getInstance();
    $clsBO = ClsBO::getInstance();

    /* lock start */
    $lockResult = $systemBatchBO->lock($filename);
    if(!$lockResult)
        Common::returnLockFailed();

    /* vars */
    $rslt = Common::getReturn();

    /* ========================= */
    /* functions for process */
    /* ========================= */
    // function sendFcmMessage($accessToken, $pushtoken, $title, $body)
    // {
    //     /**
    //      * FCM 메시지
    //      */
    //     $message = [
    //         'message' => [
    //             'token' => $pushtoken,
    //             'notification' => [
    //                 'title' => $title,
    //                 'body'  => $body,
    //             ],
    //             'data' => [
    //                 'type' => 'test',
    //                 'message_id' => '1234'
    //             ]
    //         ]
    //     ];

    //     /**
    //      * FCM HTTP v1 API
    //      */
    //     $url = 'https://fcm.googleapis.com/v1/projects/'.FCM_PRJID.'/messages:send';
    //     $ch = curl_init($url);

    //     curl_setopt_array($ch, [
    //         CURLOPT_POST => true,
    //         CURLOPT_HTTPHEADER => [
    //             'Authorization: Bearer ' . $accessToken,
    //             'Content-Type: application/json; UTF-8'
    //         ],
    //         CURLOPT_POSTFIELDS => json_encode($message),
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_TIMEOUT => 30
    //     ]);

    //     $response = curl_exec($ch);
    //     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     $curlError = curl_error($ch);
    //     curl_close($ch);

    //     /**
    //      * 결과 확인
    //      */
    //     if ($response === false)
    //         return false;
    //     return true;
    //     // echo "HTTP CODE : " . $httpCode . "\n";
    //     // echo "RESPONSE : " . $response . "\n";
    // }

    /* ========================= */
    /* process */
    /* ========================= */
    try
    {
        /* OAuth 2.0 Access Token 생성 */
        // $serviceAccountFile = FCM_KEY;
        // $credentials = new ServiceAccountCredentials(
        //     'https://www.googleapis.com/auth/firebase.messaging',
        //     json_decode(
        //         file_get_contents($serviceAccountFile),
        //         true
        //     )
        // );
        // $accessToken = $credentials->fetchAuthToken()['access_token'];

        /* --------------- */
        /* loop : system_push // 대기 중인 푸시 메시지 목록 처리 */
        /* --------------- */
        $message = null;
        $tokens = array();
        $pushWaitList = Common::getData($systemPushBO->selectWaitIngForInside());
        foreach($pushWaitList as $pushWaitModel)
        {
            $pushidx = Common::get($pushWaitModel, SystemPushBO::FIELD__PUSHIDX);
            $pushstatus = Common::get($pushWaitModel, SystemPushBO::FIELD__PUSHSTATUS);
            $pushtype = Common::get($pushWaitModel, SystemPushBO::FIELD__PUSHTYPE);
            $pushdatajson = Common::get($pushWaitModel, SystemPushBO::FIELD__PUSHDATAJSON);

            /* update pushstatus to 'Ing' */
            $systemPushBO->updatePushstatusWaitToIngForInside($pushidx);

            /* main process by pushtype */
            $pushdata = json_decode($pushdatajson, true);
            switch($pushtype)
            {
                case SystemPushBO::PUSHTYPE__CLS_OPEN:
                {
                    /* vars */
                    $grpno = Common::get($pushdata, 'grpno');
                    $clsno = Common::get($pushdata, 'clsno');

                    /* get cls */
                    $cls = $clsBO->getByPk($grpno, $clsno);
                    $grpname = Common::get($cls, 'grpname');
                    $clsstartdt = Common::get($cls, 'clsstartdt');
                    $clsground = Common::get($cls, 'clsground');

                    /* make msg */
                    $message = CloudMessage::new()
                    ->withNotification(
                        Notification::create(
                            '모임은야구 ('.$grpname.')',
                            '새로운 일정이 등록되었습니다. ('.$clsstartdt.'/'.$clsground.')'
                        )
                    )
                    ->withData([
                        'type' => 'cls',
                        'grpno' => $grpno,
                        'clsno' => $clsno,
                    ]);

                    /* 실사용자들에게 푸시 메시지 전송 */
                    $grpMemberList = Common::getData($grpMemberBO->selectActiveUsersForInside($grpno));
                    foreach($grpMemberList as $grpMember)
                    {
                        /* get */
                        $userno = Common::get($grpMember, GrpMemberBO::FIELD__USERNO);
                        $user = $userBO->getByPk($userno);
                        $pushtoken = Common::getField($user, UserBO::FIELD__PUSHTOKEN);

                        /* check if user is active */
                        if(UserBO::isActive($user) == false)
                            continue;

                        /* check if user has a push token */
                        if(Common::isEmpty($pushtoken))
                            continue;

                        /* save to tokens array */
                        // push_array($tokens, $pushtoken);
                        $tokens[] = $pushtoken;
                    }
                    break;
                }
                case SystemPushBO::PUSHTYPE__CLS_CANCEL:
                case SystemPushBO::PUSHTYPE__CLS_APPLY_START_BEFORE_10MIN:
                case SystemPushBO::PUSHTYPE__CLS_APPLY_CLOSE_BEFORE_1HOUR:
                    break;
            }

            /* send FCM message */
            Common::logDebug("count(".count($tokens).")");
            if(count($tokens) > 0)
            {
                $factory = (new Factory)->withServiceAccount(FCM_KEY);
                $messaging = $factory->createMessaging();
                $report = $messaging->send($message, $tokens);
                Common::logDebug(var_export($report, true));
            }

            // $pushcntall = Common::get($pushWaitModel, SystemPushBO::FIELD__PUSHCNTALL);
            // $pushcntsucceed = Common::get($pushWaitModel, SystemPushBO::FIELD__PUSHCNTSUCCEED);
            // $pushcntfailed = Common::get($pushWaitModel, SystemPushBO::FIELD__PUSHCNTFAILED);
            // $pushcntskipped = Common::get($pushWaitModel, SystemPushBO::FIELD__PUSHCNTSKIPPED);
            // $pushresult = Common::get($pushWaitModel, SystemPushBO::FIELD__PUSHRESULT);
            // $modidt = Common::get($pushWaitModel, SystemPushBO::FIELD__MODIDT);
            // $regdt = Common::get($pushWaitModel, SystemPushBO::FIELD__REGDT);

            /* update pushstatus to 'End' */
            // $systemPushBO->updatePushstatusIngToEndForInside($pushidx);
        }
    }
    catch(Throwable $e)
    {
        Common::logDebug(var_export($e, true));
        Common::returnError("error", $e);
    }
    finally
    {
        /* unlock */
        $systemBatchBO->unlock($filename);
    }
    Common::returnRslt($rslt);

?>
