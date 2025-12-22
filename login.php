<?php
	require_once "./inc/mysql.php";
	require_once "./inc/utils.php";
	require_once "./inc/class/Utente.php";
	require './vendor/autoload.php';

	global $prod;
	$return = false;


	if(Utente::verifyLogin()){
		header("Location: /");
	}

	putenv("GOOGLE_APPLICATION_CREDENTIALS=" . __DIR__. '/vendor/application_default_credentials.json');

	use Google\Cloud\RecaptchaEnterprise\V1\Client\RecaptchaEnterpriseServiceClient;
	use Google\Cloud\RecaptchaEnterprise\V1\Event;
	use Google\Cloud\RecaptchaEnterprise\V1\Assessment;
	use Google\Cloud\RecaptchaEnterprise\V1\CreateAssessmentRequest;
	use Google\Cloud\RecaptchaEnterprise\V1\TokenProperties\InvalidReason;

	/**
	 * Crea una valutazione per analizzare il rischio di un'azione della UI.
	 * @param string $recaptchaKey La chiave reCAPTCHA associata al sito o all'app
	 * @param string $token Il token generato ottenuto dal client.
	 * @param string $project L'ID del tuo progetto Google Cloud.
	 * @param string $action Nome dell'azione corrispondente al token.
	 */
	function create_assessment(
		string $recaptchaKey,
		string $token,
		string $project,
		string $action
	): bool
	{
		// Crea il client reCAPTCHA.
		// DA FARE: memorizza nella cache il codice di generazione del client (consigliato) o chiama client.close() prima di uscire dal metodo.
		$client = new RecaptchaEnterpriseServiceClient();
		$projectName = $client->projectName($project);

		// Imposta le proprietà dell'evento da monitorare.
		$event = (new Event())
			->setSiteKey($recaptchaKey)
			->setToken($token);

		// Crea la richiesta di test.
		$assessment = (new Assessment())
			->setEvent($event);

		$request = (new CreateAssessmentRequest())
			->setParent($projectName)
			->setAssessment($assessment);

		try {
			$response = $client->createAssessment($request);

			// Verifica che il token sia valido.
			if ($response->getTokenProperties()->getValid() == false) {
				//      printf('The CreateAssessment() call failed because the token was invalid for the following reason: ');
				//      printf(InvalidReason::name($response->getTokenProperties()->getInvalidReason()));
				return false;
			}

			// Controlla se è stata eseguita l'azione prevista.
			if ($response->getTokenProperties()->getAction() == $action) {
				// Ottieni il punteggio di rischio e i motivi.
				// Per ulteriori informazioni sull'interpretazione del test, consulta:
				// https://cloud.google.com/recaptcha-enterprise/docs/interpret-assessment
				if($response->getRiskAnalysis()->getScore() >= 0.6){

					if($_POST["username"] != "" && $_POST["password"] != ""){
						if(Utente::verifyUserLogin($_POST["username"], $_POST["password"])){
							header("Location: /");
							return true;
						}
						return false;
					}
				}
			} else {
				//printf('The action attribute in your reCAPTCHA tag does not match the action you are expecting to score');
				return false;
			}
		} catch (exception $e) {
			return false;
		}
		return false;
	}
	$captcha = $_POST["g-recaptcha-response"] ?? false;

	if(isset($_POST["login"]) && $_POST["login"] == 1 && $captcha){
		$return = create_assessment(
			'6LftbS0sAAAAAEK6J1kAl2og0TGOYm4coZcHsYEj',
			$captcha,
			'portale-docente',
			'LOGIN'
		);
	}
?>
<!doctype html>
<html lang="it">
<!--begin::Head-->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Login | Portale Docente</title>
	<!--begin::Accessibility Meta Tags-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
	<meta name="color-scheme" content="light dark" />
	<meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
	<meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
	<!--end::Accessibility Meta Tags-->
	<!--begin::Primary Meta Tags-->
	<meta name="title" content="Login | Portale Docente" />
	<!--end::Primary Meta Tags-->
	<!--begin::Accessibility Features-->
	<!-- Skip links will be dynamically added by accessibility.js -->
	<meta name="supported-color-schemes" content="light dark" />
	<link rel="preload" href="/css/adminlte.css" as="style" />
	<!--end::Accessibility Features-->
	<!--begin::Fonts-->
	<link
		rel="stylesheet"
		href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
		integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
		crossorigin="anonymous"
		media="print"
		onload="this.media='all'"
	/>
	<!--end::Fonts-->
	<!--begin::Third Party Plugin(OverlayScrollbars)-->
	<link
		rel="stylesheet"
		href="/css/overlayscrollbars.min.css"
		crossorigin="anonymous"
	/>
	<!--end::Third Party Plugin(OverlayScrollbars)-->
	<!--begin::Third Party Plugin(Bootstrap Icons)-->
	<link
		rel="stylesheet"
		href="/css/bootstrap-icons.min.css"
		crossorigin="anonymous"
	/>
	<!--end::Third Party Plugin(Bootstrap Icons)-->
	<!--begin::Required Plugin(AdminLTE)-->
	<link rel="stylesheet" href="/css/adminlte.css" />
	<script src="https://www.google.com/recaptcha/api.js"></script>
	<!--end::Required Plugin(AdminLTE)-->
</head>
<!--end::Head-->
<!--begin::Body-->
<body class="login-page bg-body-secondary">
<div class="login-box">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<a
				href="/"
				class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover"
			>
				<h1 class="mb-0">Portale Docente</h1>
			</a>
		</div>
		<div class="card-body login-card-body">
			<p class="login-box-msg">Accedi per iniziare</p>
			<form method="post" id="form">
				<input type="hidden" name="login" value="1">
				<div class="input-group mb-1">
					<div class="form-floating">
						<input id="loginEmail" type="text" name="username" class="form-control" value="" placeholder="" required />
						<label for="loginEmail">Nome Utente</label>
					</div>
					<div class="input-group-text"><span class="bi bi-envelope"></span></div>
				</div>
				<div class="input-group mb-1">
					<div class="form-floating">
						<input id="loginPassword" type="password" name="password" class="form-control" placeholder="" required />
						<label for="loginPassword">Password</label>
					</div>
					<div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
				</div>
				<?php if(!$return && isset($_POST["login"]) && $_POST["login"] == 1) {?>
				<div class="alert alert-danger" role="alert">
					Nome utente o password errati.
				</div>
				<?php } ?>
				<!--begin::Row-->
				<div class="row">
					<!-- TODO: Funzionalità da implementare: Ricordami
					<div class="col-8 d-inline-flex align-items-center">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
							<label class="form-check-label" for="flexCheckDefault"> Remember Me </label>
						</div>
					</div>-->
					<!-- /.col -->
					<div class="col-4">
						<div class="d-grid gap-2">
							<button type="submit" class="btn btn-primary g-recaptcha" data-sitekey="6LftbS0sAAAAAEK6J1kAl2og0TGOYm4coZcHsYEj" data-callback='onSubmit' data-action='LOGIN'>Accedi</button>
						</div>
					</div>
					<!-- /.col -->
				</div>
				<!--end::Row-->
			</form>
		</div>
		<!-- /.login-card-body -->
	</div>
</div>
<!-- /.login-box -->
<!--begin::Third Party Plugin(OverlayScrollbars)-->
<script
	src="/js/overlayscrollbars.browser.es6.min.js"
	crossorigin="anonymous"
></script>
<!--end::Third Party Plugin(OverlayScrollbars)-->
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/jquery-3.7.1.min.js"></script>
<script>
	function onSubmit(token) {
		$("input[name=g-recaptcha-response]").val(token);

		$("#form")[0].submit();
	}
</script>
<!--end::OverlayScrollbars Configure-->
<!--end::Script-->
</body>
<!--end::Body-->
</html>
