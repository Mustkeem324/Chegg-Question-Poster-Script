<?php 
ob_start();
$admin = YOUR_ID;
$API_KEY = 'YOUR_TOKEN'; 
define('API_KEY',$API_KEY);
echo file_get_contents("https://api.telegram.org/bot$API_KEY/setwebhook?url=".$_SERVER['SERVER_NAME']."".$_SERVER['SCRIPT_NAME']."");

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$text = $message->text;
$chat_id = $message->chat->id;
$from_id = $message->from->id;
$user = '@' . $message->from->username;
$first_name = $message->from->first_name;
$last_name = $message->from->last_name;

if (isset($message->photo)) {
    $photo = $message->photo;
}
$message_id = $update->callback_query->message->message_id;
$ex = explode(' ', $text);

//solution convert into pdf and jpg 
mkdir("points");
mkdir("allmsg");
////////////////////////
// id admin
$sudos = array(2110818173);
function get2point2days($user) {
    $file_pointer = "./points/$user.txt";

    if (file_exists($file_pointer)) {
        $exc = explode("||", file_get_contents($file_pointer));

        if ($exc[0] < 1 || strtotime($exc[1]) < strtotime("today")) {
            unlink($file_pointer);
            return [0, 0];
        } else {
            $earlier = new DateTime("now");
            $later = new DateTime($exc[1]);
            $abs_diff = $later->diff($earlier)->format("%a");
            return [$exc[0], $abs_diff + 1];
        }
    } else {
        return [0, 0];
    }
}

function del2point2days($user) {
    $file_pointer = "./points/$user.txt";

    if (file_exists($file_pointer)) {
        $exc = explode("||", file_get_contents($file_pointer));

        if ($exc[0] > 0 && strtotime($exc[1]) >= strtotime("today")) {
            $earlier = new DateTime("now");
            $later = new DateTime($exc[1]);
            $abs_diff = $later->diff($earlier)->format("%a");

            $apoints = $exc[0] - 1;

            $txt = $apoints . "||" . $exc[1];
            file_put_contents($file_pointer, $txt);

            return [$apoints, $abs_diff + 1];
        }
    }

    return [0, 0];
}

function add2point2days($user, $points, $days) {
    $file_pointer = "./points/$user.txt";

    if (file_exists($file_pointer)) {
        $exc = explode("||", file_get_contents($file_pointer));

        if (strtotime($exc[1]) >= strtotime("today")) {
            $earlier = new DateTime("now");
            $later = new DateTime($exc[1]);
            $abs_diff = $later->diff($earlier)->format("%a");
            $adays = $days + $abs_diff + 1;
            $apoints = $points + $exc[0];
        } else {
            $adays = $days;
            $apoints = $points;
        }
    } else {
        $adays = $days;
        $apoints = $points;
    }

    date_default_timezone_set("Asia/Baghdad");
    $dt = date("Y-m-d");
    $txt = $apoints . "||" . date("Y-m-d", strtotime("$dt + $adays days"));

    file_put_contents($file_pointer, $txt);
    return [$apoints, $adays];
}
//cchegg question answered update
//questionbody
function Transformlink($text){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://gateway.chegg.com/one-graph/graphql',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"operationName":"TransformUrl","variables":{"url":{"url":"'.$text.'","hostPrefix":false}},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"03e0153ed664185b1ec608f1e30ed431054d03d09e308ad0a4ff19b6e5725512"}}}',
        CURLOPT_HTTPHEADER => array(
            'authority: gateway.chegg.com',
            'accept: */*, application/json',
            'accept-language: en-US,en;q=0.9',
            'apollographql-client-name: chegg-web',
            'apollographql-client-version: main-5ab73977-5576088612',
            'authorization: Basic TnNZS3dJMGxMdVhBQWQwenFTMHFlak5UVXAwb1l1WDY6R09JZVdFRnVvNndRRFZ4Ug==',
            'content-type: application/json',
            'cookie: CVID=dd2c6013-9482-4d5d-8074-9bbbbb671c3b; V=2c39fde34e8e9db5996ae8b783e19aed64a11dea8f5c28.50689748; _pxvid=8f5ee825-18a4-11ee-9989-a6b25880e637; C=0; O=0; _pubcid=ce5a1325-8f1e-4eb0-adca-c82de3d000fe; _au_1d=AU1D-0100-001696010749-H4EZTKMX-A0HN; _pubcid_cst=zix7LPQsHA%3D%3D; _scid=50c62da3-711c-49f3-8c6a-79c596664c6f; loupeclientID=59b2979-5c0a-4649-a22b-e1b49bfc9db3; _hjSessionUser_2946394=eyJpZCI6ImQ3ODE4YzYyLTQ2OTQtNWIxZC1iMTQ5LTQ4ODZkYWVhODQxMSIsImNyZWF0ZWQiOjE2OTY3MDc4Nzc3NTgsImV4aXN0aW5nIjp0cnVlfQ==; _ga_E1732NRZXV=GS1.2.1696707877.1.0.1696707883.54.0.0; _ga_L6CX34MVT2=GS1.1.1696707876.1.1.1696708724.60.0.0; _hjSessionUser_3091164=eyJpZCI6IjJjMDhlNTRmLTAyM2YtNTQ3ZC05ZDNhLTNlMzNmNDU5ZjQ4YiIsImNyZWF0ZWQiOjE2OTcwNDIxMTczMjQsImV4aXN0aW5nIjp0cnVlfQ==; DFID=web|yXBiJB2k7XUWqDDjbm7C; permutive-id=819b3494-0fe5-4a3c-af4e-578a8162f9f8; _cc_id=e5da260b97579753866c070ad2d63164; _sctr=1%7C1698517800000; connectId=%7B%22vmuid%22%3A%22f3Ey19mzv3aO9lBwLBiirCSICDlzTNr3hNrdVVInmQ43fMPtJSlB7AQ4ZibUk-3SU6Gy4k90sA2kldAzew89fQ%22%2C%22connectid%22%3A%22f3Ey19mzv3aO9lBwLBiirCSICDlzTNr3hNrdVVInmQ43fMPtJSlB7AQ4ZibUk-3SU6Gy4k90sA2kldAzew89fQ%22%2C%22connectId%22%3A%22f3Ey19mzv3aO9lBwLBiirCSICDlzTNr3hNrdVVInmQ43fMPtJSlB7AQ4ZibUk-3SU6Gy4k90sA2kldAzew89fQ%22%2C%22ttl%22%3A24%2C%22he%22%3A%22e39006d09eeaaf335086af1daf292be816dae6c9ae8376efac8ca747eb61527d%22%2C%22lastSynced%22%3A1698674628568%2C%22lastUsed%22%3A1698757792196%7D; _awl=2.1698844334.5-67dab70069250890556a45328d8e9dcc-6763652d617369612d6561737431-0; exp_id=8feb3db37315af938ae312888419d2ef6542a43e8b4394.12677425; ab.storage.sessionId.49cbafe3-96ed-4893-bfd9-34253c05d80e=%7B%22g%22%3A%22bb647ad0-85ac-eb33-f950-8f8f05e42af2%22%2C%22e%22%3A1698868038842%2C%22c%22%3A1698866238846%2C%22l%22%3A1698866238846%7D; ab.storage.deviceId.49cbafe3-96ed-4893-bfd9-34253c05d80e=%7B%22g%22%3A%22272aaef5-6d65-d1fd-6a96-e441f30f85e9%22%2C%22c%22%3A1697130622703%2C%22l%22%3A1698866238849%7D; ab.storage.userId.49cbafe3-96ed-4893-bfd9-34253c05d80e=%7B%22g%22%3A%22c58e0d65-c105-461b-8c24-1e4181ce9f8c%22%2C%22c%22%3A1697559560745%2C%22l%22%3A1698866238851%7D; optimizelyEndUserId=oeu1699029012366r0.3548941515510595; _cs_c=0; _cs_id=37d65b65-3442-aa80-fcb3-23cb552916eb.1699029013.1.1699029036.1699029013.1.1733193013405; _scid_r=50c62da3-711c-49f3-8c6a-79c596664c6f; forterToken=de6d955102dd49499306913113a72b06_1699936025257__UDF43-m4_13ck; U=0; _iidt=0HDx9EhhoDle/dKH/vsjGK1MixJlctKDAvvD/Hdy3L7Sql6Jq7MEEfv6bH4/CgofudP8TlWVDcLr1md/6qkFnMFyow==; _vid_t=Y1ldmm8kFFPtv9cno7pmZbbmsM/lcU0NNm0B2TB68rzUVe1vpn84zyzb9i8u3rKP+zWNFe308iXVTzLHzPSvvwnB2Q==; panoramaId_expiry=1700814356849; panoramaId=791ea04a092fd847902d5ef0525916d5393804009d9170b350a0d032dd3e428d; panoramaIdType=panoIndiv; opt-user-profile=dd2c6013-9482-4d5d-8074-9bbbbb671c3b%252C24080330904%253A24091301300%252C24105130281%253A24093410251%252C24483060027%253A24374571107%252C24639721375%253A24665850370%252C24407410763%253A24408150549%252C24985571146%253A24944641121%252C25230140379%253A25249890628%252C25233851370%253A25293620061%252C26210340118%253A26222240070; _ga_HRYBF3GGTD=GS1.1.1700224592.16.0.1700224594.0.0.0; _ga_1Y0W4H48JW=GS1.1.1700224592.16.1.1700224594.0.0.0; _ga=GA1.2.1290475448.1696010748; exp=C026A%7CA127D; expkey=65A93264A6F0D70DBA7B125AA92FC04A; SU=VNRSH7IeJJ5AVgCHJ-HYadsauEvl5C1yEXZHnKwja5ROPobCt6fM3AipqwM1DyTZqx-CjNn-hRTErs11Lz6Ioxv0ATlCFdjoUT6zMNGbEvNO9ExDUlYaTPolJHkRdHMt; country_code=IN; pxcts=63a82b97-869f-11ee-8ef1-1dcdab27ec13; CSID=1700494241547; _gid=GA1.2.1902144065.1700494254; _au_last_seen_pixels=eyJhcG4iOjE3MDA0OTQyNTMsInR0ZCI6MTcwMDQ5NDI1MywicHViIjoxNzAwNDk0MjUzLCJydWIiOjE3MDA0OTQyNTMsInRhcGFkIjoxNzAwNDk0MjUzLCJhZHgiOjE3MDA0OTQyNTMsImdvbyI6MTcwMDQ5NDI1MywidW5ydWx5IjoxNzAwNDk0MzM1LCJpbXByIjoxNzAwNDk0MzM1LCJ0YWJvb2xhIjoxNzAwNDk0MzM1LCJzb24iOjE3MDA0OTQyNTMsIm9wZW54IjoxNzAwNDk0MzM1LCJpbmRleCI6MTcwMDQ5NDI1MywiYW1vIjoxNzAwNDk0MjUzLCJhZG8iOjE3MDA0OTQzMzUsImNvbG9zc3VzIjoxNzAwNDk0MzM1LCJzbWFydCI6MTcwMDQ5NDMzNSwicHBudCI6MTcwMDQ5NDMzNSwiYmVlcyI6MTcwMDQ5NDMzNX0%3D; __gads=ID=39136120173e9d1a:T=1689703704:RT=1700498709:S=ALNI_MZ4Hdp-B35cUffShw4vvTWEhYpVcw; __gpi=UID=00000c2204e0fa5b:T=1689703704:RT=1700498709:S=ALNI_MajvCfR9zxGPRCB3ngLKLgjCkZJ_w; refreshToken=ext.a0.t00.v1.MzTIsWsp055y9pzZ9if0Sx2botKKGPqbUe3HtoGp6q8Yw9tvzUWRfaPhmL7C4O-Bvt21Jg74BQsjhVrMYRnWtKaG; refresh_token=ext.a0.t00.v1.MzTIsWsp055y9pzZ9if0Sx2botKKGPqbUe3HtoGp6q8Yw9tvzUWRfaPhmL7C4O-Bvt21Jg74BQsjhVrMYRnWtKaG; id_token=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6Im11c3RrZWVtMzI0QGdtYWlsLmNvbSIsImlzcyI6Imh1Yi5jaGVnZy5jb20iLCJhdWQiOiJDSEdHIiwiaWF0IjoxNzAwNDk5ODMxLCJleHAiOjE3MTYwNTE4MzEsInN1YiI6IjRjYzI3NThiLTEzMDMtNDBlNi1hNjI1LTcyZGQ2ZmRkMTEwMSIsInJlcGFja2VyX2lkIjoiYXB3IiwiY3R5cCI6ImlkIiwiaWRzaWQiOiI2N2JhZmNmNiIsImlkc3QiOjE3MDA0OTk4MzExNzcsImlkc2ciOiJ1bmtub3duIn0.xY28GPxUqiHZLuWDgqTrUYCjRLl3MDh873tLRifTUcczie4JuOdwJtP7P_UgLoZWEp15dlcOoCSbPxlhYDevk_zSkgp4JYDJ_fZqjMpxbkIeP-mPLFAqxp1jtC4OVl9jq04G4uaL3VmqymR1nr4edUQ0kW8z3xtMvy8sSc3xCqPW-4Itjk4sgck2_LPFZRo_U7iXB2Ygm9tpZ5LN4F2P-tKCF3SnnHU0ZA1YSJiTl-zNyiKE3YkTY2CWrQmon9mvqphCpOjullBy6ChxHgB6cPuz8ibxcp3u3LxRk9ZemLg7P7gLC4oq-Z7l47tftf4rWzjv7Es7xQe8O3j7GPYQsg; access_token=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwczovL3Byb3h5LmNoZWdnLmNvbS9jbGFpbXMvYXBwSWQiOiJDSEdHIiwiaXNzIjoiaHViLmNoZWdnLmNvbSIsInN1YiI6IjRjYzI3NThiLTEzMDMtNDBlNi1hNjI1LTcyZGQ2ZmRkMTEwMSIsImF1ZCI6WyJ0ZXN0LWNoZWdnIl0sImlhdCI6MTcwMDQ5OTgzMSwiZXhwIjoxNzAwNTAxMjcxLCJhenAiOiJGaXBqM2FuRjRVejhOVVlIT2NiakxNeDZxNHpWS0VPZSIsInNjb3BlIjoib3BlbmlkIHByb2ZpbGUgZW1haWwgYWRkcmVzcyBwaG9uZSBvZmZsaW5lX2FjY2VzcyIsImd0eSI6WyJyZWZyZXNoX3Rva2VuIiwicGFzc3dvcmQiXSwicmVwYWNrZXJfaWQiOiJhcHciLCJjdHlwIjoiYWNjZXNzIn0.lx-cPKQTJuJu0KdmAKkcuGb49nt0ZzQQiLI51wwxcuCVhFsJPaPHVgHMxhJ-6ihik36exADCBAga-F715wtif1yGlrAfFaElTw7aspS3zkgQSxOss50MW5z30Osjqz9L5z1gewyIbn0hPbTUFcZatrP6CVtaJFpurXLYxkaM0cAPaZjhxpcuLpNUGGbO67hnloghqixzDYG4bOuEPAjO-qX0RUbUWtQXVGrIHyaeSprdviER0n6mJL-qcQ0uiLN_JkDAtMzSV5R3oJs1JBdBsztWcyYvlSDZdcLNC8SGof0i_i_LjY6iiWEuDe1BTuVt5BMmR5x5HxZFTCyiCnEBMg; access_token_expires_at=1700501271185; OptanonConsent=isGpcEnabled=0&datestamp=Mon+Nov+20+2023+22%3A43%3A23+GMT%2B0530+(India+Standard+Time)&version=202310.2.0&isIABGlobal=false&hosts=&consentId=c13a1fdb-4315-4338-8ba1-bb2ae973da03&interactionCount=1&landingPath=NotLandingPage&groups=fnc%3A0%2Csnc%3A1%2Ctrg%3A0%2Cprf%3A0&AwaitingReconsent=false&browserGpcFlag=0; _px3=fc1711027cac2a7de138804598ea41cfbc61da41616edaa479d9f3b315358ca9:zLB34mSXJhcI6kqCGt46pP5R26/aMRVp6RHd/dAOJW8BKOEvnZBp5ROVcyMLSvlwiN8tRIfUM9ZvBcRJswwb5w==:1000:Fq7E6i8FpeCAcV0DNN3cWl6138Ap9Ma1A3Bwz5ECtlDBhsuu/v8ZHYTn0am1gvq9TQ6WrN+GgUqzoX13BvtvlYGMk1JnFWScBedtgYIfnmrunTfLf5Aiw0mRAcbFKDZ1MFkbCykTFM4WhNpdFCb9ET211WtT0mPLzOu5QyEgFULaShfS4LyVQlgweioGY/B4cbjfZ17VJNPYhPoRhyPHK9X0TAiMXMfYOwPHDocPfPM=; _px=zLB34mSXJhcI6kqCGt46pP5R26/aMRVp6RHd/dAOJW8BKOEvnZBp5ROVcyMLSvlwiN8tRIfUM9ZvBcRJswwb5w==:1000:j0nLC65uzLm9ohirkRArw+63k1fx1Gpwmn6S4yuf1K5FX0Q/r14v6SagWujWzHyyfMqeGpp5xyO3b2HgOuWyA/b4ZxSr7uaWaucjFszn2mUmCh/jOnfcMUcEiawauWvuvgiKtGPShq3/fWuBJzWefLk1HNMZYLjisB33TWo1+LKRC4FDYeA+Zk0Ng/D6hmM1pv3gc22pC1UMaYSPbSyupR4Ja9amQzHSQUkqDGfIXovgmNQx+wsta4hF33KSxy5SPSA8vaJKTEc1TjOVeTKF5g==; _pxde=948419dd98245493be647a04539858b4c8f576c98811e9a6391b48b418264c63:eyJ0aW1lc3RhbXAiOjE3MDA1MDA2NTU5MDN9',
            'dnt: 1',
            'origin: https://www.chegg.com',
            'referer: https://www.chegg.com/',
            'sec-ch-ua: "Chromium";v="118", "Google Chrome";v="118", "Not=A?Brand";v="99"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Linux"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-site',
            'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36'
            ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    //echo $response;
    $data =json_decode($response,true);
    $uuidstring =$data['data']['transformUrl']['iosDeeplinkEncoded'];
    $parts = explode('%2F', $uuidstring);
    $uuid = end($parts);
    return $uuid;
}
function questionBody($uuid) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://gateway.chegg.com/one-graph/graphql',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{"operationName":"QuestionById","variables":{"uuid":"'.$uuid.'"},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"6fb6122e78f35ff4ef1005cadc05efa7359480ce0581b949ae946fef51659f59"}}}',
        CURLOPT_HTTPHEADER => array(
            'authority: gateway.chegg.com',
            'accept: */*, application/json',
            'accept-language: en-US,en;q=0.9',
            'apollographql-client-name: chegg-web',
            'apollographql-client-version: main-5ab73977-5576088612',
            'authorization: Basic TnNZS3dJMGxMdVhBQWQwenFTMHFlak5UVXAwb1l1WDY6R09JZVdFRnVvNndRRFZ4Ug==',
            'content-type: application/json',
            'cookie: CVID=d1f9c6c8-ec7b-4399-9b4b-d2ff2f278c23; V=b0434a206185d1248be05d0946eae46264326471c20c48.54596028; _scid=6c255f11-eeeb-4952-9f47-f526ef3502ac; OneTrustWPCCPAGoogleOptOut=true; _pxvid=adcf6dfd-d6bc-11ed-8e6d-0faf9f3ee8a1; C=0; O=0; exp=C026A; sbm_country=IN; _pubcid=ca273e29-6149-470c-a834-33f240966d09; _lr_env_src_ats=false; gid=1; gidr=MA; pbjs-unifiedid_cst=0Cw6LNAs7Q%3D%3D; _pubcid_cst=zix7LPQsHA%3D%3D; permutive-id=a8230da6-f7e3-4894-822f-5ee4d8850c78; _ga=GA1.1.323224641.1698858627; _hjSessionUser_3091164=eyJpZCI6ImQ4MTY0ZTRkLWZkZTctNTY0OS05OGRkLTA2MTU0NGY3ZjA0ZCIsImNyZWF0ZWQiOjE2OTg4NTg2MjY2NTQsImV4aXN0aW5nIjp0cnVlfQ==; expkey=031A203D5924CDC5B25EB8E2333DF41C; exp_id=c902e93cd0626069a79faa54c3bcd02a654558a8f95810.66458786; ab.storage.sessionId.49cbafe3-96ed-4893-bfd9-34253c05d80e=%7B%22g%22%3A%22e5ac116f-243c-5471-fc60-267be20c5a74%22%2C%22e%22%3A1699045297078%2C%22c%22%3A1699043497079%2C%22l%22%3A1699043497079%7D; ab.storage.deviceId.49cbafe3-96ed-4893-bfd9-34253c05d80e=%7B%22g%22%3A%22e37fbffa-c67e-998e-455e-e6c03776bf68%22%2C%22c%22%3A1699043497086%2C%22l%22%3A1699043497086%7D; _ga_1Y0W4H48JW=GS1.1.1699043401.3.1.1699043675.0.0.0; _ga_HRYBF3GGTD=GS1.1.1699043400.3.1.1699043675.0.0.0; _sp_id.ad8a=8598e398-146e-457a-ad6c-1ef557423766.1696510704.4.1699680974.1698097029.30db20cc-8a0f-459f-b64a-824613ad4e52; opt-user-profile=b0434a206185d1248be05d0946eae46264326471c20c48.54596028%2C24115930466%3A24157310052%2C24985571146%3A24987031066; _vid_t=0f2pITEI2LF8uIGMF1tT5v18DlnwBpT9bjW45MyUPSlwXJGNcvDWWkxSUIgV77AUbG2EpwC2BAc/viEn9qSzJqcQTw==; DFID=web|yXBiJB2k7XUWqDDjbm7C; _pbjs_userid_consent_data=3524755945110770; _sharedid=000d51b9-597f-42d5-b061-9809491ad0c9; pbjs-unifiedid=%7B%22TDID%22%3A%223bc55e1c-a5d4-4638-b877-8d9a5af9ce43%22%2C%22TDID_LOOKUP%22%3A%22TRUE%22%2C%22TDID_CREATED_AT%22%3A%222023-10-15T11%3A23%3A53%22%7D; connectId=%7B%22vmuid%22%3A%22KySDV45ROVyHmZA4nFaxcz9r2Ly3IzoTzFHASjHpbJdjgz0g9u4H1U_ZRIbZjS6nR9cg_jk2U5SJCl9oq7dH-g%22%2C%22connectid%22%3A%22KySDV45ROVyHmZA4nFaxcz9r2Ly3IzoTzFHASjHpbJdjgz0g9u4H1U_ZRIbZjS6nR9cg_jk2U5SJCl9oq7dH-g%22%2C%22connectId%22%3A%22KySDV45ROVyHmZA4nFaxcz9r2Ly3IzoTzFHASjHpbJdjgz0g9u4H1U_ZRIbZjS6nR9cg_jk2U5SJCl9oq7dH-g%22%2C%22ttl%22%3A24%2C%22he%22%3A%225ecf6d62dc5d50beea8797f84ab271ff4dc634f5f4fb408cfdf46be53bc94e02%22%2C%22lastSynced%22%3A1700047434018%2C%22lastUsed%22%3A1700047434018%7D; connectid=%7B%22vmuid%22%3A%22KySDV45ROVyHmZA4nFaxcz9r2Ly3IzoTzFHASjHpbJdjgz0g9u4H1U_ZRIbZjS6nR9cg_jk2U5SJCl9oq7dH-g%22%2C%22connectid%22%3A%22KySDV45ROVyHmZA4nFaxcz9r2Ly3IzoTzFHASjHpbJdjgz0g9u4H1U_ZRIbZjS6nR9cg_jk2U5SJCl9oq7dH-g%22%2C%22connectId%22%3A%22KySDV45ROVyHmZA4nFaxcz9r2Ly3IzoTzFHASjHpbJdjgz0g9u4H1U_ZRIbZjS6nR9cg_jk2U5SJCl9oq7dH-g%22%2C%22ttl%22%3A24%2C%22he%22%3A%225ecf6d62dc5d50beea8797f84ab271ff4dc634f5f4fb408cfdf46be53bc94e02%22%2C%22lastSynced%22%3A1700047434018%2C%22lastUsed%22%3A1700047434018%7D; connectid_cst=0Cw6LNAs7Q%3D%3D; _sctr=1%7C1700159400000; __gads=ID=36304ba78a4a5160:T=1700047432:RT=1700253304:S=ALNI_MZ295J3vDa5ej2Bd0IXVMeI1TBoPw; __gpi=UID=00000c87d1bc609c:T=1700047432:RT=1700253304:S=ALNI_MYhff7elPkh8EWtC5_2gHFU8OB9uw; _awl=2.1700253381.5-c627b420b5c568406859e7b7638f97e9-6763652d617369612d6561737431-1; usprivacy=1YYY; _scid_r=6c255f11-eeeb-4952-9f47-f526ef3502ac; country_code=IN; hwh_order_ref=/homework-help/questions-and-answers/task2-curriculum-bachelor-science-task-create-knowledge-base-describing-courses-prerequisi-q119964027; CSID=1700499233437; pxcts=6547d14b-87c5-11ee-bf10-2842ecff1d6f; schoolapi=null; PHPSESSID=62flk55dgq1863cihkpquhbu78; CSessionID=b335218c-5a62-4353-8f1e-3e114296be20; user_geo_location=%7B%22country_iso_code%22%3A%22IN%22%2C%22country_name%22%3A%22India%22%2C%22region%22%3A%22DL%22%2C%22region_full%22%3A%22National+Capital+Territory+of+Delhi%22%2C%22city_name%22%3A%22Delhi%22%2C%22postal_code%22%3A%22110008%22%2C%22locale%22%3A%7B%22localeCode%22%3A%5B%22en-IN%22%2C%22hi-IN%22%2C%22gu-IN%22%2C%22kn-IN%22%2C%22kok-IN%22%2C%22mr-IN%22%2C%22sa-IN%22%2C%22ta-IN%22%2C%22te-IN%22%2C%22pa-IN%22%5D%7D%7D; _pxff_fp=1; sbm_a_b_test=1-control; ftr_blst_1h=1700499241699; _cc_id=5224b6adb348393dbd27b58a4198feea; panoramaId_expiry=1701104043476; panoramaId=d7959165730aa3084965a088e79516d5393847885fb7239a0429c8ad63ad8577; panoramaIdType=panoIndiv; forterToken=051b65f74be54aecbe9e6cd210cc9e81_1700499240767__UDF43-m4_13ck; id_token=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6Im11c3RrZWVtb2ZmaWNpYWxAZ21haWwuY29tIiwiaXNzIjoiaHViLmNoZWdnLmNvbSIsImF1ZCI6IkNIR0ciLCJpYXQiOjE3MDA0OTkyNTAsImV4cCI6MTcxNjA1MTI1MCwic3ViIjoiNzdkZGU2ZDgtZGIwYi00YzkwLTgyMzUtMzk1YzFlYmU0Yzg3IiwicmVwYWNrZXJfaWQiOiJhcHciLCJjdHlwIjoiaWQiLCJpZHNpZCI6IjEyZGJkODYzIiwiaWRzdCI6MTcwMDQ5OTI1MDU1NiwiaWRzZyI6InBhc3N3b3JkbGVzcyJ9.OAkMcZUd_pOsNyZzAqIyq9VLjc3BFNw_TnS7baSx3IAZMce1U2DXL3VbRLNNvwz29jMISVQPaTet8b64hBEU7wXyN2JT8rncmcnlrG-fo1KH0gJiWuLcVzu9X9Z-unIspH2xUQp26J-iMzmbz4li5lFb7Ach5V_tfM973hNWzeiWezE8ajJ2RMA73ZInfi188xwAgCVs8HwEhE0KK1q8IBcG4WCKs6qv8LXAQz9GbgJX6dQ_Zpweuh18j1ghbrQKJpnSJS2wk2dcAGPUAEZIgsV4SUV4_NzQyaGUctV5VpMoeln8WyIXXkvnE5zQa0JO2Bdnvk1yepoeNTOCOoI5vw; access_token=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwczovL3Byb3h5LmNoZWdnLmNvbS9jbGFpbXMvYXBwSWQiOiJDSEdHIiwiaXNzIjoiaHViLmNoZWdnLmNvbSIsInN1YiI6Ijc3ZGRlNmQ4LWRiMGItNGM5MC04MjM1LTM5NWMxZWJlNGM4NyIsImF1ZCI6WyJ0ZXN0LWNoZWdnIiwiaHR0cHM6Ly9jaGVnZy1wcm9kLmNoZWdnLmF1dGgwLmNvbS91c2VyaW5mbyJdLCJpYXQiOjE3MDA0OTkyNTAsImV4cCI6MTcwMDUwMDY5MCwiYXpwIjoiRmlwajNhbkY0VXo4TlVZSE9jYmpMTXg2cTR6VktFT2UiLCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIGFkZHJlc3MgcGhvbmUgb2ZmbGluZV9hY2Nlc3MiLCJndHkiOiJwYXNzd29yZCIsInJlcGFja2VyX2lkIjoiYXB3IiwiY2hnaHJkIjp0cnVlLCJjdHlwIjoiYWNjZXNzIn0.sFEXOHtw_2GqIhUqve-fOMudZu1b5h1pEWr8YywiD5rxfl0dO0smsnM4xLahzycOTylFZIs2AHBw60IKlL1DDuwL8NHmmQedKsUlba-DeP8eShySf5btWijVIGKh-7-M1pxXow3iquV_CDEHcn4GG0YalQP4laC6q6huxeTmmtOAYfO-Xp-7lNTu7BTGVmEgdXvJHR_9PV4x71_Yi1CtAsy0UY-_9mZ0iu5kPlJZFzW_bW1ZRgVtx5ZwQSZ_S3yc2wI3BqPw-O5uJk_1tD_MBLs1_mo3lj9RgefV_FvYDtGW1vv2UZ-HienaVhsggH3QMnvPOXlGl_gsMjumIu26xg; U=35e10940cc1e405f15beb1aa71c2eecd; SU=cgFvt3lxMoj2nk36qeMlDtBVR434GeWMgb6kH_oUpvZ342Jfjja7zQMGcOPECCyCguX5mCrpVMHh9J9GCvgVAY9vnJWiR36GHxYY0cq7QNwz5j416uHwJYg9P-JTeN4Q; OptanonConsent=isGpcEnabled=0&datestamp=Mon+Nov+20+2023+22%3A24%3A16+GMT%2B0530+(India+Standard+Time)&version=202310.2.0&isIABGlobal=false&hosts=&consentId=ec54b66b-f6a4-4247-8a8a-d0311a89fe7d&interactionCount=1&landingPath=NotLandingPage&groups=fnc%3A0%2Csnc%3A1%2Ctrg%3A0%2Cprf%3A0&AwaitingReconsent=false&browserGpcFlag=0; _px3=57ec2b6d526da01f42a30ac6d49681513fe427c70b4eae93bc6c5014495d69a4:946lUVQ4sH0OiYszVxKgx8E4vgzBayxW0uthG2SvP6pqEIRRDEDGj6YZ5S3rVsKT3H2j7Sj+rJ4RceLUlS6Y+A==:1000:4nfPJWldk4gWKjt5AvoopdLb1VnDMqNQB7T3qF1pW528FruTofUT3bZMo1UagUEjfdOMr+9bArV0a7g+pCs4YqtpmS5Whki0Tpx2qewQ+WpMP9/SDvu82ErCJdreTeM3mrrRHxJw45EAyi5tyUnnGbz7oZi0/rx+LL617mYqjME/o54ruCKdhLCEffepRnqLNVv05rLShk4kw1x9PJMabXFX8TBOh2vN6mQ4Tpl67V4=; _px=946lUVQ4sH0OiYszVxKgx8E4vgzBayxW0uthG2SvP6pqEIRRDEDGj6YZ5S3rVsKT3H2j7Sj+rJ4RceLUlS6Y+A==:1000:3w4jtF3awNF+UNeSSy7Ha918mL7NJGC+vMh60lw/YORQWPUnmhzYK7GXzXP9xUM0n73oQEmvjK1oKTwL8Y4eCKy1ut2i4K7wZdqVWHmSH62vSn6xK080cO6++0AVNA1Ti/mgzqCYrildo+QRdico/dkU7F+cVyQVQxv9DbcY5PVMNL7v6JAHL43+6Yr0a4VK3mJsJFc4suF9kTa8GZRZTelgwVAO2vkBl+tsjyJcTENXBZWdDDXIxmYe/FbH0TO6RiLPRgoZf6x0nAb/CWZjaQ==; _pxde=6b1c1d8e0736f7489d8ba26072b05c3fb65c3c43e10a9cd3d119abf7b6225cc6:eyJ0aW1lc3RhbXAiOjE3MDA0OTkyNjExMDd9',
            'dnt: 1',
            'origin: https://www.chegg.com',
            'referer: https://www.chegg.com/',
            'sec-ch-ua: "Chromium";v="118", "Google Chrome";v="118", "Not=A?Brand";v="99"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Linux"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-site',
            'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36'
        ),
    )
    );

    $response = curl_exec($curl);

    curl_close($curl);
    //echo $response;
    $data =json_decode($response,true);
    $questionState =$data['data']['questionByUuid']['questionState'];
    $lastUpdated = $data['data']['questionByUuid']['lastUpdated'];
    $expertFeedback = $data['data']['questionByUuid']['expertFeedback'];

    return [$questionState,$lastUpdated,$expertFeedback];

}
///////////Subject ID//////////////
$subjectfile = "https://fanswer.net/cheggpost/subject.json";
$data = json_decode(file_get_contents($subjectfile), true);

function getSubjectIdByName($subjectName, $data) {
    foreach ($data['data']['qnaLegacySubjects'] as $subjectGroup) {
        foreach ($subjectGroup['subjects'] as $subject) {
            if ($subject['name'] == $subjectName) {
                return $subject['id'];
            }
        }
    }

    return null; // return null if the subject name isn't found
}

////////////////////////

if ($text == "/get" || $text == "/get@FREEPORTCHEGGPOSTSBOT") {
    $aa = get2point2days($from_id);
    $directory = "./points";
    $files = scandir($directory);
    $num_files = count($files) - 2;

    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "Your points: $aa[0]\nYour points expire after: $aa[1] days\nNumber VIP: $num_files",
        'reply_to_message_id' => $message->message_id,
        'parse_mode' => 'markdown',
        'disable_web_page_preview' => true
    ]);
}

if (preg_match('/give/', $text) && in_array($from_id, $sudos)) {
    preg_match_all("/\\d+/", $text, $matches);
    $tr = add2point2days($matches[0][2], $matches[0][0], $matches[0][1]);
    
    bot('sendMessage', [
        'chat_id' => $matches[0][2], 
        'text' => "Points added!✅\nYour points: $tr[0]\nYour points expire after: $tr[1] days\n\nFor More Point Contact owner: @spacenx1",
        'parse_mode' => 'markdown',
        'disable_web_page_preview' => true
    ]);
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "Your points: $tr[0]\nYour points expire after: $tr[1] days",
        'reply_to_message_id' => $message->message_id,
        'parse_mode' => 'markdown',
        'disable_web_page_preview' => true
    ]);
}

if (preg_match('/\/del (\d+)/', $text, $matches) && in_array($from_id, $sudos)) {
    $reply_id = $matches[1]; // Extracting the {id} from the command

    $file_path = "./points/{$reply_id}.txt";

    if (file_exists($file_path)) {
        unlink($file_path);

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "Deleted file: $reply_id.txt",
            'reply_to_message_id' => $message->message_id,
            'parse_mode' => 'markdown',
            'disable_web_page_preview' => true
        ]);
    } else {
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "File not found: $reply_id.txt",
            'reply_to_message_id' => $message->message_id,
            'parse_mode' => 'markdown',
            'disable_web_page_preview' => true
        ]);
    }
}
//chegglink check
if(preg_match('/chegg.com/' ,$text)){
    $uuid = Transformlink($text);
    $questionbodyhtml=questionBody($uuid);
    $dateString = $questionbodyhtml[1];
    $exertfeedreivew = $questionbodyhtml[2];
    $dateTime = new DateTime($dateString);
    $date = $dateTime->format('Y-m-d'); // Extracting date
    $time = $dateTime->format('H:i:s'); // Extracting time
    if($questionbodyhtml[0]==='Answered'){
        bot('sendMessage', [
            'chat_id' => $chat_id,
            "text" => "🌟Hey $user,Your Question are Solved 😍😍:\n\nQuestion Link: $text\n\nCopy the link and send it in the same group\n\nDate of solving a question: $date || $time",'reply_to_message_id' => $message->chat_id
            ]);
    }
    elseif($questionbodyhtml[0]==='NeedMoreInfo'){
        bot('sendMessage', [
            'chat_id' => $chat_id,
            "text" => "🌟Hey $user,Your Question was incompleted ❤️‍🩹❤️‍🩹:\n\nExpert feddback: $exertfeedreivew\n\nQuestion Link: $text\n\n\nExpert Raised isuue date: $date || $time",'reply_to_message_id' => $message->chat_id
            ]);
    }
    elseif($questionbodyhtml[0]===null){
        bot('sendMessage', [
            'chat_id' => $chat_id,
            "text" => "🌟Hey $user,Your Question was not Solved ❤️‍🩹❤️‍🩹:\n\nOne of our experts is on it! Most questions are answered within two hours, but response time can vary. We will let you know when your solution is ready.\n\nQuestion Link: $text\n\nExpert Raised isuue date: $date || $time",'reply_to_message_id' => $message->chat_id
            ]);
    }
}
//date and defualt timezone
date_default_timezone_set("Asia/Baghdad");

//joined & left button
$join = bot('getChatMember', ["chat_id" => "@cheggnx", "user_id" => $from_id])->result->status;
$join2 = bot('getChatMember', ["chat_id" => "@CheggbyTnTbot", "user_id" => $from_id])->result->status;

if (($newchat_id != '' || $leftchat_id != '') || ($message && ($join == 'left' || $join == 'kicked' || $join2 == 'left' || $join2 == 'kicked'))) {
    bot('deleteMessage', [
        'chat_id' => $chat_id,
        'message_id' => $message->message_id
    ]);

    $response = bot('sendMessage', [
        'chat_id' => $from_id,
        'text' => "Welcome to My Premium Bot 🔓🔰. You must subscribe to the channels to use the bot for free.",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '• Join Channel 1 - ', 'url' => 'https://t.me/cheggnx']],
                [['text' => '• Join Channel 2 - ', 'url' => 'https://t.me/CheggbyTnTbot']]
            ]
        ])
    ]);

    if ($response && $message) {
        bot('sendMessage', [
            'chat_id' => $from_id,
            'text' => "Replying to your message:",
            'reply_to_message_id' => $message->message_id
        ]);
    }
    exit('A_god');
}

$idu = array(-1001622658730,$admin,$from_id);
	

//cheggg new though api 
if ($photo && in_array($chat_id, $idu)) {
    $folder = 'postimage/';
    if (!file_exists($folder)) {
        mkdir($folder, 0755, true); // The third argument creates parent directories if they don't exist
    }
    $file = "https://api.telegram.org/file/bot" . API_KEY . "/" . bot('getfile', ['file_id' => $message->photo[sizeof($photo) - 1]->file_id])->result->file_path;
    $file_contents = file_get_contents($file);
    $file_local_full = $folder . 'img_' . $from_id . '.png';
    file_put_contents($file_local_full, $file_contents);
    $filesize = filesize($file_local_full);
    $stream = fopen($file_local_full, 'r');
    $id_url = 'https://nxpro.aba.vg/' . $file_local_full;
    echo "File downloaded successfully";

    $pi = get2point2days($from_id);

    if ($pi[0] < 1 || $pi[1] < 1) {
        // Handle the case when the user doesn't have enough points.
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "Points must be purchased to get the answer.... to buy!",
            'reply_to_message_id' => $message->message_id,
            'parse_mode' => 'markdown',
            'disable_web_page_preview' => true,
            'reply_markup' => json_encode([
                'inline_keyboard' => [[
                    ['text' => 'Contact Here 💚', 'url' => 't.me/spacenx1']
                ]]
            ])
        ]);
        exit();
    }
    if ($message->photo) {
        // User sent a photo, extract the file_id of the photo
        $photo = end($message->photo);
        $file_id = $photo->file_id;
    
        // Get the subject name from the caption or user's message
        $subjectName = $message->caption;
    
        // Find the subject ID by name
        $subjectId = getSubjectIdByName($subjectName, $data);
    
        if ($subjectId !== null) {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Your Selected Subject topic: $subjectName and ID: $subjectId",
                'reply_to_message_id' => $message->chat_id,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true
            ]);
        } else {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "Your Selected $subjectName Subject topic was not found",
                'reply_to_message_id' => $message->message_id,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true
            ]);
        }
    } else {
        // User didn't send a photo, display the subject list
        bot('sendMessage',[
            'chat_id'=>$chat_id,
            "text"=>"Subject list:
        Send Subject Topic name as caption   with question!   
            
        Math:-
        1. <code>Prealgebra</code>
        2. <code>Geometry</code>
        3. <code>Algebra</code>
        4. <code>Trigonometry</code>
        5. <code>Precalculus</code>
        6. <code>Calculus</code>
        7. <code>Statistics and Probability</code>
        8. <code>Advanced Math</code>
        9. <code>Other Math</code>
        
        Science:-
        1. <code>Physics</code>
        2. <code>Chemistry</code>
        3. <code>Biology</code>
        4. <code>Anatomy and Physiology</code>
        5. <code>Earth Sciences</code>
        6. <code>Advanced Physics</code>
        7. <code>Nursing</code>
        
        Engineering:-
        1. <code>Mechanical Engineering</code>
        2. <code>Civil Engineering</code>
        3. <code>Computer Science</code>
        4. <code>Electrical Engineering</code>
        5. <code>Chemical Engineering</code>
        
        Business:-
        1. <code>Accounting</code>
        2. <code>Finance</code>
        3. <code>Economics</code>
        4. <code>Operations Management</code>
        
        Writing Help:-
        1. <code>Prewriting</code>
        2. <code>Postwriting</code>
        
        English:-
        1. <code>Poetry</code>
        2. <code>Literature</code>
        3. <code>Communications</code>
        
        History:-
        1. <code>World History</code>
        2. <code>American History</code>
        3. <code>European History</code>
        
        Social Sciences:-
        1. <code>Psychology</code>
        2. <code>Sociology</code>
        3. <code>Anthropology</code>
        4. <code>Political Science</code>
        5. <code>International Relations</code>
        6. <code>Philosophy</code>
        ",'reply_to_message_id'=>$message->chat_id,'parse_mode'=>'HTML','disable_web_page_preview'=>'True'
            ]);
    }   
    //cookies checking is valid or not 
    function getRandomCookie($jsonFilePath) {
        $jsonContents = file_get_contents($jsonFilePath);
        $cookieArray = json_decode($jsonContents, true);
        if ($cookieArray === null) {
            return 'Error decoding JSON.';
        } else {
            $cookies = $cookieArray['cookies'];
            if (!empty($cookies)) {
                // Shuffle the cookies array
                shuffle($cookies);
                return $cookies;
            } else {
                return array();
            }
        }
    }
     
    function checkAndUpdateCookie($cookies, $jsonFilePath) {
        foreach ($cookies as $cookie) {
            $curltrue = curl_init();
            curl_setopt_array($curltrue, array(
                CURLOPT_URL => 'https://gateway.chegg.com/one-graph/graphql',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{"operationName":"hasActiveCheggStudy","variables":{},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"f6707940e697a3a359f218b04ad23eee36b4d11cea2b8b59221a572fdd8c554b"}}}',
                CURLOPT_HTTPHEADER => array(
                    'authority: gateway.chegg.com',
                    'accept: */*, application/json',
                    'accept-language: en-US,en;q=0.9',
                    'apollographql-client-name: chegg-web',
                    'apollographql-client-version: main-47f7e7d4-5403504590',
                    'authorization: Basic TnNZS3dJMGxMdVhBQWQwenFTMHFlak5UVXAwb1l1WDY6R09JZVdFRnVvNndRRFZ4Ug==',
                    'content-type: application/json',
                    'cookie: '.$cookie,
                    'dnt: 1',
                    'origin: https://www.chegg.com',
                    'referer: https://www.chegg.com/',
                    'sec-ch-ua: "Google Chrome";v="113", "Chromium";v="113", "Not-A.Brand";v="24"',
                    'sec-ch-ua-mobile: ?0',
                    'sec-ch-ua-platform: "Linux"',
                    'sec-fetch-dest: empty',
                    'sec-fetch-mode: cors',
                    'sec-fetch-site: same-site',
                    'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36',
                    ),
                ));
            $responsetrue = curl_exec($curltrue);
            curl_close($curltrue);
            $responseDatatrue = json_decode($responsetrue, true);
            if ($responseDatatrue['data']['me']['hasCheggStudy'] === true){
                $curl = curl_init();
    
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://gateway.chegg.com/one-graph/graphql',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{"operationName":"resetBanner","variables":{},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"c78e259d8e022c643865405d193982aaa3c4a8167ea978dda04ce6b440cfdb55"}}}',
                    CURLOPT_HTTPHEADER => array(
                        'authority: gateway.chegg.com',
                        'accept: */*, application/json',
                        'accept-language: en-US,en;q=0.9',
                        'apollographql-client-name: chegg-web',
                        'apollographql-client-version: main-9484a536-5354791451',
                    'authorization: Basic TnNZS3dJMGxMdVhBQWQwenFTMHFlak5UVXAwb1l1WDY6R09JZVdFRnVvNndRRFZ4Ug==',
                        'content-type: application/json',
                        'cookie:'.$cookie,
                        'dnt: 1',
                        'origin: https://www.chegg.com',
                        'referer: https://www.chegg.com/',
                        'sec-ch-ua: "Google Chrome";v="113", "Chromium";v="113", "Not-A.Brand";v="24"',
                        'sec-ch-ua-mobile: ?0',
                        'sec-ch-ua-platform: "Linux"',
                        'sec-fetch-dest: empty',
                        'sec-fetch-mode: cors',
                        'sec-fetch-site: same-site',
                        'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36'
                    ),
                ));
        
                $response = curl_exec($curl);
                curl_close($curl);
        
                // Parse the response
                $responseData = json_decode($response, true);
                $account_status=$responseData['data']['me']['accountSharing']['userStatus'];
                //echo $response;
                if ($responseData['data']['me']['accountSharing']['userStatus'] === 'RELEASED' || $responseData['data']['me']['accountSharing']['userStatus'] === 'OK') {
                    // If hasCheggStudy is true, return the cookie
                    return $cookie;
                }
            }    
        }
    
        // If no valid cookies are found, return an empty string
        return '';
    }
    $cookiefile=file_get_contents('https://nx.aba.vg/nxcode/cookieschegg.json');
    $jsonFilePath = "cookieschegg.json";
    $cookies = getRandomCookie($jsonFilePath);
    $validCookie = checkAndUpdateCookie($cookies, $jsonFilePath);
    if($validCookie !==null){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://proxy.chegg.com/content/media',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('file'=> new CURLFILE($file_local_full),'autoOrient' => 'false'),
            CURLOPT_HTTPHEADER => array(
                'authority: proxy.chegg.com',
                'accept: application/json, text/plain, */*',
                'accept-language: en-US,en;q=0.9',
                'dnt: 1',
                'origin: https://www.chegg.com',
                'referer: https://www.chegg.com/',
                'sec-ch-ua: "Chromium";v="118", "Google Chrome";v="118", "Not=A?Brand";v="99"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Linux"',
                'sec-fetch-dest: empty',
                'sec-fetch-mode: cors',
                'sec-fetch-site: same-site',
                'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        //echo $response;
        $data = json_decode($response,true);
        if($data['httpCode']===200){
            $imageuri =$data['result']['uri'];
            if (isset($imageuri)){
                //bot('sendMessage', ['chat_id' => $chat_id,"text" => "Your Posted Question:$imageuri",'reply_to_message_id' => $message->message_id]);
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://gateway.chegg.com/one-graph/graphql',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{"operationName":"postQuestionV2","variables":{"body":"<div><p>Solved</p><div><img src=\''.$imageuri.'\' /></div>","toExpert":true,"subjectId":'.$subjectId.'},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"05828a42a1758cf26bb9db02eb598e776a72c48d2cca742d2cc489fd7dbe5f28"}}}',
                    CURLOPT_HTTPHEADER => array(
                        'authority: gateway.chegg.com',
                        'accept: */*, application/json',
                        'accept-language: en-US,en;q=0.9',
                        'apollographql-client-name: chegg-web',
                        'apollographql-client-version: main-495aaff9-5526928173',
                        'authorization: Basic TnNZS3dJMGxMdVhBQWQwenFTMHFlak5UVXAwb1l1WDY6R09JZVdFRnVvNndRRFZ4Ug==',
                        'content-type: application/json',
                        'cookie: '.$validCookie,
                        'dnt: 1',
                        'origin: https://www.chegg.com',
                        'referer: https://www.chegg.com/',
                        'sec-ch-ua: "Chromium";v="118", "Google Chrome";v="118", "Not=A?Brand";v="99"',
                        'sec-ch-ua-mobile: ?0',
                        'sec-ch-ua-platform: "Linux"',
                        'sec-fetch-dest: empty',
                        'sec-fetch-mode: cors',
                        'sec-fetch-site: same-site',
                        'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36',
                        'x-chegg-referrer: chat?show-resub-oneclick=true'
                    ),
                ));

                $response2 = curl_exec($curl);
                curl_close($curl);
                //echo $response;
                $data2= json_decode($response2,true);
                if($data2['data']['postQuestionV2']['askedByMe']===true){
                    $organicUrl=$data2['data']['postQuestionV2']['organicUrl'];
                    $organicUrllink="https://www.chegg.com/".$organicUrl;
                    // Deduct the points after a successful request.
                    $pi = del2point2days($from_id);
                    //echo $organicUrllink;
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        "text" => "Posted in $subjectName Subject :)\n\nQuestion Link : $organicUrllink\n\n☑We've sent your question to the experts!",
                        'reply_to_message_id' => $message->message_id
                    ]);
                    bot('sendMessage', ['chat_id' => $chat_id,"text" => "Your Posted Question:$imageuri",'reply_to_message_id' => $message->message_id]);
                }else{
                    bot('sendMessage', [
                        'chat_id' => $chat_id,
                        "text" => "Posted in $subjectName Subject :)\n\nError,Contact Owner....",
                        'reply_to_message_id' => $message->message_id
                    ]);
                }
            }
            else{
                bot('sendMessage', [
                    'chat_id' => $chat_id,
                    "text" => "Sorry image not upload done....",
                    'reply_to_message_id' => $message->message_id
                ]);
            }
        }
    }
}elseif ($photo && !in_array($chat_id, $idu)) {
    bot('sendMessage', [
        'chat_id' => $chat_id,
        "text" => "bot works in groups only\n@cheggnx",
        'reply_to_message_id' => $message->message_id
    ]);
}


//update auomatically answered
if ($questionbodyhtml[0] === 'Answered') {
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "🌟Hey $user, Your Question is Solved 😍😍:\n\nQuestion Link: $text\n\nCopy the link and send it in the same group\n\nDate of solving a question: $questionbodyhtml[1]",
        'reply_to_message_id' => $chat_id
    ]);
}

//start
if($text == "/start"){
bot('sendMessage', [
'chat_id' => $chat_id,
"text" => "🌟Welcome $first_name in Chegg posting

Post Your Questions on Chegg Instantly To Be Solved By Tutor💯

Rule: Below Given Subject Topic list copy the topic and share with image as caption

Owner: @spacenx1",
'reply_to_message_id' => $message->chat_id
]);
bot('sendMessage', [
'chat_id' => $chat_id,
"text" => "Contact : @spacenx1


✨Other BOTS LIST✨

Bot : @CHEGGUPVOTESBOT- Working to  Chegg Upvote

Bot : @VIPEGGBOT- Working to unblurr Chegg & Solutioninn

Bot : @OcrSearchbot - Working to search Questions-and-answers chegg & bartleby link using image or text

ALL BOTS ARE WORKING FINE NOW

OWNER : @spacenx1 for payment or any other issues

THANK You ❤️
",
'reply_to_message_id' => $message->chat_id
]);   
bot('sendMessage',[
'chat_id'=>$chat_id,
"text"=>"Subject list:

Math:-
1. <code>Prealgebra</code>
2. <code>Geometry</code>
3. <code>Algebra</code>
4. <code>Trigonometry</code>
5. <code>Precalculus</code>
6. <code>Calculus</code>
7. <code>Statistics and Probability</code>
8. <code>Advanced Math</code>
9. <code>Other Math</code>

Science:-
1. <code>Physics</code>
2. <code>Chemistry</code>
3. <code>Biology</code>
4. <code>Anatomy and Physiology</code>
5. <code>Earth Sciences</code>
6. <code>Advanced Physics</code>
7. <code>Nursing</code>

Engineering:-
1. <code>Mechanical Engineering</code>
2. <code>Civil Engineering</code>
3. <code>Computer Science</code>
4. <code>Electrical Engineering</code>
5. <code>Chemical Engineering</code>

Business:-
1. <code>Accounting</code>
2. <code>Finance</code>
3. <code>Economics</code>
4. <code>Operations Management</code>

Writing Help:-
1. <code>Prewriting</code>
2. <code>Postwriting</code>

English:-
1. <code>Poetry</code>
2. <code>Literature</code>
3. <code>Communications</code>

History:-
1. <code>World History</code>
2. <code>American History</code>
3. <code>European History</code>

Social Sciences:-
1. <code>Psychology</code>
2. <code>Sociology</code>
3. <code>Anthropology</code>
4. <code>Political Science</code>
5. <code>International Relations</code>
6. <code>Philosophy</code>
",'reply_to_message_id'=>$message->chat_id,'parse_mode'=>'HTML','disable_web_page_preview'=>'True'
]);

}

?>
