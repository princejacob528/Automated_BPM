<?php
$aid=$_POST['userKey'];
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <title>Automated BPM</title>
    <link rel="icon" href="../../img/logo.png">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>   
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>   
</head>

<body>
    <!-- Vue Js App Code -->
    <div id="app">        
        <div class="section" style="width: 50vh; align-items: center; margin: auto;">
            <qrcode-scanner 
                v-bind:qrbox="250"
                v-bind:fps="10"
                style="width: 50vh;">
            </qrcode-scanner>
        </div>       
    </div>
    <form action="../../php/light/dashboard.php" method="POST" id="successForm">
    <input value="<?php echo $aid; ?>" type="text" name="userKey" style="display: none;">
    <input value="set" type="text" name="checks" style="display: none;">
    <input type="text" name="attstr" id="attstr" style="display: none;">
    </form>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="html5-qrcode.min.js"></script>
    <script>
        Vue.component('qrcode-scanner', {
            props: {
                qrbox: Number,
                fps: Number,
            },
            template: `<div id="qr-code-full-region"></div>`,
            mounted: function () {
                var $this = this;
                var config = { fps: this.fps ? this.fps : 10 };
                if (this.qrbox) {
                    config['qrbox'] = this.qrbox;
                }

                function onScanSuccess(qrCodeMessage) {
                    $this.$root.$emit('decodedQrCode', qrCodeMessage);
                    html5QrcodeScanner.clear();
                }

                var html5QrcodeScanner = new Html5QrcodeScanner(
                    "qr-code-full-region", config);
                html5QrcodeScanner.render(onScanSuccess);
            }
        });

        var app = new Vue({
            el: '#app',
            data: {
                header: '',
                result: ''
            },
            created: function () {
                this.$root.$on('decodedQrCode', function (qrCodeMessage) {
                    this.decodedMessage = qrCodeMessage;
                    document.getElementById("attstr").value = qrCodeMessage;                    
                    document.getElementById("successForm").submit();                    
                }.bind(this));
            }
        });
    </script>
</body>

</html>