<?php

    /* ========================= */
    /* init */
    /* ========================= */
    include '../env/env.php';
    require_once ROOT. '/vendor/autoload.php';

    use Kreait\Firebase\Factory;
    use Kreait\Firebase\Messaging\CloudMessage;
    use Kreait\Firebase\Messaging\Notification;

    /* filename */
    $filename = basename(__FILE__, ".php");

    /* BO */
    GGnavi::getSystemBatchBO();
    GGnavi::getSystemPushBO();
    GGnavi::getGrpMemberBO();
    GGnavi::getUserBO();
    GGnavi::getClsBO();
    GGnavi::getGrpBO();
    $systemBatchBO = SystemBatchBO::getInstance();
    $systemPushBO = SystemPushBO::getInstance();
    $grpMemberBO = GrpMemberBO::getInstance();
    $userBO = UserBO::getInstance();
    $clsBO = ClsBO::getInstance();
    $grpBO = GrpBO::getInstance();

    /* lock start */
    $lockResult = $systemBatchBO->lock($filename);
    if(!$lockResult)
        Common::returnLockFailed();

    /* vars */
    $rslt = Common::getReturn();

    /* ========================= */
    /* process */
    /* ========================= */
    try
    {
        /* --------------- */
        /* loop : system_push // 대기 중인 푸시 메시지 목록 처리 */
        /* --------------- */
        $message = null;
        $tokenArr = array();
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
                    $clsstartdtFormatted = date("Y-m-d H:i", strtotime($clsstartdt));
                    $clsground = Common::get($cls, 'clsground');

                    /* make msg */
                    $message = CloudMessage::new()
                    ->withNotification(
                        Notification::create(
                            '새로운일정 ('.$grpname.')',
                            $clsstartdtFormatted.' / '.$clsground
                        )
                    )
                    ->withData([
                        'type' => 'cls',
                        'grpno' => $grpno,
                        'clsno' => $clsno,
                    ]);

                    /* 활성유저의 푸시토큰 가져오기 */
                    $tokenArr = $grpBO->getTokenOfActiveUsersByGrpno($grpno);
                    break;
                }
                case SystemPushBO::PUSHTYPE__CLS_CANCEL:
                {
                    /* vars */
                    $grpno = Common::get($pushdata, 'grpno');
                    $clsno = Common::get($pushdata, 'clsno');

                    /* get cls */
                    $cls = $clsBO->getByPk($grpno, $clsno);
                    $grpname = Common::get($cls, 'grpname');
                    $clsstartdt = Common::get($cls, 'clsstartdt');
                    $clsstartdtFormatted = date("Y-m-d H:i", strtotime($clsstartdt));
                    $clsground = Common::get($cls, 'clsground');
                    $clscancelreason = Common::get($cls, 'clscancelreason');

                    /* make msg */
                    $message = CloudMessage::new()
                    ->withNotification(
                        Notification::create(
                            '일정취소 ('.$grpname.')',
                            $clsstartdtFormatted.' / '.$clsground.' / '.$clscancelreason
                        )
                    )
                    ->withData([
                        'type' => 'cls',
                        'grpno' => $grpno,
                        'clsno' => $clsno,
                    ]);

                    /* 활성유저의 푸시토큰 가져오기 */
                    $tokenArr = $grpBO->getTokenOfActiveUsersByGrpno($grpno);
                    break;
                }
                case SystemPushBO::PUSHTYPE__CLS_APPLY_START_BEFORE_10_MIN:
                {
                    /* vars */
                    $grpno = Common::get($pushdata, 'grpno');
                    $clsno = Common::get($pushdata, 'clsno');

                    /* get cls */
                    $cls = $clsBO->getByPk($grpno, $clsno);
                    $grpname = Common::get($cls, 'grpname');
                    $clsstartdt = Common::get($cls, 'clsstartdt');
                    $clsstartdtFormatted = date("Y-m-d H:i", strtotime($clsstartdt));
                    $clsground = Common::get($cls, 'clsground');

                    /* make msg */
                    $message = CloudMessage::new()
                    ->withNotification(
                        Notification::create(
                            '모집시작 10분전 ('.$grpname.')',
                            $clsstartdtFormatted.' / '.$clsground
                        )
                    )
                    ->withData([
                        'type' => 'cls',
                        'grpno' => $grpno,
                        'clsno' => $clsno,
                    ]);

                    /* 활성유저의 푸시토큰 가져오기 */
                    $tokenArr = $grpBO->getTokenOfActiveUsersByGrpno($grpno);
                    break;
                }
                case SystemPushBO::PUSHTYPE__CLS_APPLY_CLOSE_BEFORE_1_HOUR:
                {
                    /* vars */
                    $grpno = Common::get($pushdata, 'grpno');
                    $clsno = Common::get($pushdata, 'clsno');

                    /* get cls */
                    $cls = $clsBO->getByPk($grpno, $clsno);
                    $grpname = Common::get($cls, 'grpname');
                    $clsstartdt = Common::get($cls, 'clsstartdt');
                    $clsstartdtFormatted = date("Y-m-d H:i", strtotime($clsstartdt));
                    $clsground = Common::get($cls, 'clsground');

                    /* make msg */
                    $message = CloudMessage::new()
                    ->withNotification(
                        Notification::create(
                            '모집마감 1시간 전 ('.$grpname.')',
                            $clsstartdtFormatted.' / '.$clsground
                        )
                    )
                    ->withData([
                        'type' => 'cls',
                        'grpno' => $grpno,
                        'clsno' => $clsno,
                    ]);

                    /* 활성유저의 푸시토큰 가져오기 */
                    $tokenArr = $grpBO->getTokenOfActiveUsersByGrpno($grpno);
                    break;
                }
            }

            /* send FCM message */
            Common::logDebug("count(".count($tokenArr).")");
            if(count($tokenArr) > 0)
            {
                $factory = (new Factory)->withServiceAccount(FCM_KEY);
                $messaging = $factory->createMessaging();
                $report = $messaging->sendMulticast($message, $tokenArr);
            }

            /* update pushstatus to 'End' */
            // $systemPushBO->updatePushstatusIngToEndForInside($pushidx);
        }
    }
    catch(Throwable $e)
    {
        Common::returnError("error", $e);
    }
    finally
    {
        /* unlock */
        $systemBatchBO->unlock($filename);
    }
    Common::returnRslt($rslt);

?>
