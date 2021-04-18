function abre_arquivo(onde){
			var iframe = document.getElementById("main");
			iframe.src = onde;

		}

		function showMessageBox() {
			abre_arquivo("mensagens/listaMensagens.php");
		}

		function fechar(){
			document.getElementById("acao").value = 'encerrar_sessao';
			document.getElementById("main_form").submit();
		}