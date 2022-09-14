<template>
	<body class="font-sans antialiased">
		<div>
			<div class="min-w-screen min-h-screen bg-gray-200 flex items-center justify-center px-1 sm:px-5 pb-10 pt-16 flex-col">
				<div class="w-full mx-auto rounded-lg bg-white shadow-lg p-5 text-gray-700" style="max-width: 600px">
					<div class="w-full pt-1 pb-5">
						<div v-if="type == 'card'" class="bg-white text-white overflow-hidden rounded-full w-20 h-20 -mt-16 mx-auto shadow-lg flex justify-center items-center"> <img style="margin-left: 2mm; max-width: 75%;" src="https://i.ibb.co/PYd7MSn/free-png-ru-734.png" width="80" height="60" alt=""> </div>
						<div v-else class="bg-white text-white overflow-hidden rounded-full w-20 h-20 -mt-16 mx-auto shadow-lg flex justify-center items-center"> <img style="margin-left: 2mm;margin-top: 1.5mm; max-width: 75%;" src="img/qiwi.svg" width="80" height="60" alt=""> </div>
					</div>
					<div class="mb-10">
						<h1 class="text-center font-bold text-lg uppercase">Информация о платеже</h1> </div>
					<div class="mb-3">
						<div v-if="type == 'card'" class="font-bold text-sm mb-2 ml-1">1. Перейдите в приложение вашего банка</div>
						<div v-else class="font-bold text-sm mb-2 ml-1">1. Перейдите в приложение QIWI</div>

						<div v-if="type == 'card'" class="font-bold text-sm mb-2 ml-1">2. Выберите <span class="text-blue-500 font-bold">Переводы</span> &rarr; <span class="text-blue-500 font-bold">По номеру карты</span></div>
						<div v-else class="font-bold text-sm mb-2 ml-1">2. Выберите <span class="text-blue-500 font-bold">Переводы</span> &rarr; <span class="text-blue-500 font-bold">На QIWI Кошелек</span></div>
						<div v-if="type == 'card'" class="font-bold text-sm mb-2 ml-1">3. В поле <span class="text-blue-500 font-bold">Номер карты</span> скопируйте номер</div>						
						<div v-else class="font-bold text-sm mb-2 ml-1">3. В поле <span class="text-blue-500 font-bold">Номер телефона</span> скопируйте номер</div>

						<div class="flex">
							<input ref="wallet" v-on:focus="$event.target.select()" readonly="" id="number" class="w-full px-3 py-2 border-2 border-gray-200 rounded-l-md focus:outline-none focus:border-indigo-500 transition-colors" type="text" v-model="wallet">
							<button @click="copyWallet" class="copy-btn flex items-center px-3 rounded-r-md border-2 border-l-0 focus:outline-none border-gray-200 bg-gray-50 text-gray-500 text-sm hover:bg-gray-100"> Копировать </button>
						</div>
					</div>
					<div class="mb-3">
						<div v-if="type == 'card'" class="font-bold text-sm mb-2 ml-1">4. В поле <span class="text-blue-500 font-bold">Сумма платежа</span> скопируйте сумму <br> <span style="color: red;">(в точности) Сумма указана без учета комиссии банка. ПОЛУЧАТЕЛЬ ДОЛЖЕН ПОЛУЧИТЬ {{ sum }}</span></div>
						<div v-else class="font-bold text-sm mb-2 ml-1">4. В поле <span class="text-blue-500 font-bold">Сумма</span> скопируйте сумму <br> <span style="color: red;">(в точности) Сумма указана без учета комиссии системы. ПОЛУЧАТЕЛЬ ДОЛЖЕН ПОЛУЧИТЬ {{ sum }}</span></div>
						<div class="flex">
							<input ref="sum" v-on:focus="$event.target.select()" name="suma" id="amount" readonly="" class="w-full px-3 py-2 border-2 border-gray-200 rounded-l-md focus:outline-none focus:border-indigo-500 transition-colors" v-model="sum" type="text">
							<button @click="copySum" data-what="amount" class="copy-btn flex items-center px-3 rounded-r-md border-2 border-l-0 focus:outline-none border-gray-200 bg-gray-50 text-gray-500 text-sm hover:bg-gray-100"> Копировать </button>
						</div>
					</div>

					<div class="font-bold text-sm mb-2 ml-1"><span class="text-red-500 font-bold">!Неверная сумма зачислена не будет!</span></div>

					<div class="mb-7 font-bold text-sm ml-1" style="text-align: justify;">5. Убедитесь в том, что все данные совпадают с данной формой и нажмите <span class="text-blue-500 font-bold">Перевести. </span>После оплаты нажмите кнопку <span class="text-blue-500 font-bold">Я оплатил</span>. Начнется проверка платежа, после успешной проверки Вас перенаправит на сайт</div>
					<div> <img style="margin: auto; width: 100px;" v-show="spinner" src="https://i.ibb.co/8N62s5k/Bars-1s-200px.gif">
						<p style="margin: auto;display: table;font-size: 18px;" class="text-blue-500 font-bold" v-show="!cancelpay && checking">Проверяем оплату</p>
						<p style="margin: auto;display: table;font-size: 18px;" class="text-blue-500 font-bold" v-show="cancelpay && !checking">Отменяем оплату</p>
						<p style="margin: auto;display: table;font-size: 18px; color: #fff; padding: 15px 40px;" class="text-green-500 border-green-500 bg-green-300 rounded-md font-bold" v-show="success.show">{{ success.message }}</p>
						<p style="margin: auto;display: table;font-size: 18px; color: #fff; padding: 15px 40px;" class="text-red-500 border-red-500 bg-red-300 rounded-md font-bold" v-show="errors.show">{{ errors.message }}</p>
						<div v-if="type == 'card'" style="display: table; margin: auto;">
							<button v-show="!spinner && !success.show && !errors.show" @click="check" style=" font-size: 16px; padding: 15px 40px; margin-right: 10px;" id="check-mob" class="btn btn-primary btn-lg">Я оплатил</button>
							<button v-show="!spinner && !success.show && !errors.show" @click="cancel" style=" font-size: 16px; padding: 15px 40px;" class="btn btn-danger btn-lg">Отменить платеж</button>
						</div>
						<div v-if="type == 'qiwi'" style="display: table; margin: auto;">
							<button v-show="!spinner && !success.show && !errors.show" @click="check" style=" font-size: 16px; padding: 15px 40px; margin-right: 10px;" id="check-mob" class="btn btn-primary btn-lg">Я оплатил</button>
							<button v-show="!spinner && !success.show && !errors.show" @click="cancel" style=" font-size: 16px; padding: 15px 40px;" class="btn btn-danger btn-lg">Отменить платеж</button>
							<!-- <button v-show="!spinner && !success.show && !errors.show && type =='qiwi'" @click="toQiwi" style=" font-size: 16px; padding: 15px 40px; margin-right: 10px;" class="btn btn-success btn-lg">Оплата через сайт QIWI</button> -->
						</div>
						<!-- <div v-if="type == 'qiwi'" style="display: table; margin: auto; margin-top: 10px;">
							<button v-show="!spinner && !success.show && !errors.show" @click="cancel" style=" font-size: 16px; padding: 15px 40px;" class="btn btn-danger btn-lg">Отменить платеж</button>
						</div> -->
					</div>
				</div>
			</div>
		</div>
	</body>
</template>
<script>
export default {
	data() {
			return {
				wallet: '',
				sum: '',
				type: '',
				link: '',
				errors: {
					show: false,
					message: ''
				},
				disableBtn: false,
				spinner: false,
				cancelpay: false,
				checking: false,
				success: {
					show: false,
					message: '',
				},
			}
		},
		created() {
			this.getPayment();
		},
		methods: {
			toQiwi() {
				setTimeout(window.open(this.link, '_blank'));
			},
			getPayment() {
					var id = this.$route.query.id;
					if(id) {
						this.$root.axios.get('/payment/get/' + id).then(res => {
							this.wallet = res.data.wallet;
							this.wallet = this.wallet.replace(/ /g,'');
							this.sum = res.data.sum;
							this.type = res.data.type;
							this.link = res.data.link;
						});
					} else {
						window.location.href = "/";
					}
				},
				check() {
					if(this.disableBtn) {
						return;
					}
					this.disableBtn = true;
					this.errors.show = false;
					this.spinner = true;
					this.checking = true;
					var id = this.$route.query.id;
					var payment = this.$route.query.payment;
					var timerId = setInterval(function() {
						this.$root.axios.get('/payment/check/'+id)
	                .then(res => {
	                    const data = res.data;
	                    if (data.success) {
	                    	clearInterval(timerId);
	                    	this.spinner = false;
	                    	this.checking = false;
	                    	this.success.show = true;
	                    	this.success.message = "Успешная оплата";
	                    	setTimeout(window.location.href = "/", 2500);
	                    } else if (data.error) {
							clearInterval(timerId);
	                    	this.spinner = false;
	                    	this.checking = false;
		                	this.errors.show = true;
		                	this.errors.message = "Платёж не найден";
	                    } else {
	                    	return;
	                    }
	                });
					}.bind(this), 2000);
				},
				cancel() {
					var id = this.$route.query.id;
					var payment = this.$route.query.payment;
					this.cancelpay = true;
					this.spinner = true;
					this.$root.axios.get('/payment/cancel/'+id).then(res => {
						setTimeout(window.location.href = "/", 2500);
					});
				},
				copyWallet() {
					this.$refs.wallet.focus();
					document.execCommand('copy');
				},
				copySum() {
					this.$refs.sum.focus();
					document.execCommand('copy');
				},
		}
}
</script>
<style type="text/css">
	@media (max-width: 768px) {
		.btn-lg {
			padding: .6rem 1rem !important;
		}
	}
	@media (max-width: 375px) {
		#check-mob {
			width: auto;
			display: flex;
			margin: auto;
			margin-bottom: 10px;
			margin-right: auto !important;
		}
		.p-5 {
		    padding: 1.5rem !important;
		}
	}
</style>