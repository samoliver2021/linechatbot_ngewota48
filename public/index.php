<?php
	require __DIR__ . '/../vendor/autoload.php';

	use Psr\Http\Message\ResponseInterface as Response;
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Slim\Factory\AppFactory;
	
	use \LINE\LINEBot;
	use LINE\LINEBot\Event\MessageEvent\TextMessage;
	use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
	use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
	use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
	use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
	use \LINE\LINEBot\SignatureValidator as SignatureValidator;
	use \LINE\LINEBot\MessageBuilder\VideoMessageBuilder;

	use function PHPSTORM_META\type;

	$pass_signature = true;

	$channel_access_token = "xQrJtQ7je7t4wC08WBiJzIMEIa3piOVmY8S0zetgCN7nHy1JI20rItSh3CvUuvElpmNty6r85LVzxtEsxGKn3aq0YCXWUA7O9870yzepRxcTsOvr0ryBvnurQrXuDqcPG23JNHclHannyg0Is38ERQdB04t89/1O/w1cDnyilFU=";
	$channel_secret = "df7fc8c3d61186baa5d4ad25cf1b0704";


	$httpClient = new CurlHTTPClient($channel_access_token);
	$bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);

	$app = AppFactory::create();
	$app->setBasePath("/public");

	$app->post('/webhook',function(Request $request, Response $response) use ($channel_secret,$bot,$httpClient,$pass_signature) {
		$body = $request->getBody();
		$signature = $request->getHeaderLine('HTTP_X_LINE_SIGNATURE');

		file_put_contents('php://stderr','Body: ' . $body);

		if ($pass_signature === false) {
			if (empty($signature)) {
				return $response->withStatus(400, 'Signature not set');
			}

			if (!SignatureValidator::validateSignature($body,$channel_secret,$signature)) {
				return $response->withStatus(400, 'Invalid signature');
			}
		}

		$data = json_decode($body, true);
		if (is_array($data['events'])) {
			foreach($data['events'] as $event) {
				if ($event['type'] == 'message') {
					if($event['message']['type'] == 'text') {
						$message = strtolower($event['message']['text']);
						if ($message == 'ultah') {
							$flexTemplate = file_get_contents("../ultah.json");
							$result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
								'replyToken' => $event['replyToken'],
								'messages'   => [
									 [
										  'type'     => 'flex',
										  'altText'  => 'Flex message ultah JKT 48',
										  'contents' => json_decode($flexTemplate)
									 ]
								],
						  ]);
						}

						else if ($message == 'event') {
							$flexTemplate = file_get_contents("../event.json");
							$result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
								'replyToken' => $event['replyToken'],
								'messages'   => [
									 [
										  'type'     => 'flex',
										  'altText'  => 'Flex message event JKT 48',
										  'contents' => json_decode($flexTemplate)
									 ]
								],
						  ]);
						}

						else if ($message == 'news') {
							$flexTemplate = file_get_contents("../news.json");
							$result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
								'replyToken' => $event['replyToken'],
								'messages'   => [
									 [
										  'type'     => 'flex',
										  'altText'  => 'Flex message news JKT 48',
										  'contents' => json_decode($flexTemplate)
									 ]
								],
						  ]);
						}

						else if ($message == 'video') {
							$textSR = new TextMessageBuilder("Berikut ini adalah video terakhir yang dipost di channel JKT 48 TV");
							$vidSR = new VideoMessageBuilder("https://www.youtube.com/watch?v=BsNKBsbuap4&t=502s", "https://i.ytimg.com/vi/BsNKBsbuap4/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLABsvX3HejfJdRzkoz_AYorbSpBCA");
							$srBuilder = new MultiMessageBuilder();
							$srBuilder->add($textSR);
							$srBuilder->add($vidSR);
							$bot->replyMessage($event['replyToken'], $srBuilder);
						}

						else if ($message == 'member') {
							$flexTemplate = file_get_contents("../member.json");
							$result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
								'replyToken' => $event['replyToken'],
								'messages'   => [
									 [
										  'type'     => 'flex',
										  'altText'  => 'Flex message member JKT 48',
										  'contents' => json_decode($flexTemplate)
									 ]
								],
						  ]);
						}

						else if ($message == 'song') {
							$flexTemplate = file_get_contents("../song.json");
							$result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
								'replyToken' => $event['replyToken'],
								'messages'   => [
									 [
										  'type'     => 'flex',
										  'altText'  => 'Flex message lagi JKT 48',
										  'contents' => json_decode($flexTemplate)
									 ]
								],
						  ]);
						}

						else {
							$textFM1 = new TextMessageBuilder("Maaf, sepertinya kakak salah tulis keywordnya. Coba diperiksa lagi ya !");
							$textFM2 = new TextMessageBuilder("event-> Informasi mengenai event terkini yang diikuti JKT 48" . "\n" . "\n" . "news -> Berita terkini mengenai JKT 48" . "\n" . "\n" .  "ultah -> List member yang ulang tahun bulan ini" . "\n" . "\n" . "song -> Rekomendasi lagu-lagu JKT 48" . "\n" . "\n" .  "member -> Preview beberapa member saat ini JKT 48" . "\n" . "\n" . "video -> Video terbaru dari JKT 48 TV");
							$stickerFM3 = new StickerMessageBuilder(11537,52002770);
							$mmBuilder = new MultiMessageBuilder();
							$mmBuilder->add($textFM1);
							$mmBuilder->add($textFM2);
							$mmBuilder->add($stickerFM3);

							$bot->replyMessage($event['replyToken'],$mmBuilder);
						}

						$response->getBody()->write(json_encode($result->getJSONDecodedBody()));
						return $response
							->withHeader('Content-Type', 'application/json')
							->withStatus($result->getHTTPStatus());
					}
				}
			}
			return $response->withStatus(200, 'for Webhook!');
		}
		return $response->withStatus(400,'No event sent!');

	});

	$app->run();
?>