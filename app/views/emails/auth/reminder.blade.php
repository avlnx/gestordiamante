<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Atualizar Senha</h2>

		<div>
			Para atualizar sua senha, complete este formulário: {{ URL::to('password/reset', array($token)) }}.
		</div>
	</body>
</html>