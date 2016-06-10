<?php
    	
    if(isset($_GET['id'])){
        $idSubasta = $_GET['id'];
    }
    Conexion($idSubasta);

    function RedirectToURL($url, $tiempo)
    {
        header("Refresh: $tiempo, URL=$url");
        exit;
    }

    function Conexion($idSubasta){
        $conn = new mysqli("localhost", "643b4d11c092cc1e", "sekret", "643b4d11c092cc1ea32a96bdb4f8da93");
		$selectSubastas;
		$resultSubastas;
    	if(session_id() == '') {
            session_start();
		}
        //$selectPujas = "SELECT id FROM usuarios WHERE id = '".$_SESSION['user']['postor']."'";
        //$resultSubastas = $conn->query($selectSubastas);
        $select = "SELECT id, tipo FROM usuarios WHERE usuario='postor' AND password='sekretp'";
	   $result = $conn->query($select);
	   $idUser = '';
           $result->num_rows;
           $row = $result->fetch_assoc();
           $idUser = $row['id'];
           id($idUser);
		
       
    }

    if(!empty($_POST['puja']))
    {
        echo "CACA";
    }
    

?>
    <html>

    <body>
        <!-- Puja -->
        <a class="active">
                <form id='login' class="input-list style-4 clearfix" action='dinamicaDescAscendente.php?id=<?php echo $idSubasta; ?>' method='post' accept-charset='UTF-8'>
                    <input type='number' name='puja' id='puja' style="width:100px;" required />
                    <button name='submit'> Puja echo  </button>
                    <?php function id($idUser){ echo $idUser;} ?>
                </form>
            </a>

        <script type="text/javascript">
            $(document).ready(function () {
                refreshTable();
            });

            function refreshTable() {
                $('#tableHolder').load('dinamicaDescAscendente.php', function () {
                    setTimeout(refreshTable, 5000);
                });
            }
        </script>
    </body>

    </html>