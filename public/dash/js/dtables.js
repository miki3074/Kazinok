"use strict";
function getFormattedDate(today) 
{
    // var week = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    // var day  = week[today.getDay()];
    var dd   = today.getDate();
    var mm   = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    var hour = today.getHours();
    var minu = today.getMinutes();
	var sec = today.getSeconds();

    if(dd<10)  { dd='0'+dd } 
    if(mm<10)  { mm='0'+mm } 
    if(minu<10){ minu='0'+minu } 
	if(sec<10){ sec='0'+sec } 
    return dd+'-'+mm+'-'+yyyy+' '+hour+':'+minu+':'+sec;
}
var KTDatatablesData = function() {

	var initUsers = function() {
		var table = $('#users');

		table.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				url: "/admin/load",
				type: "POST",
				data: {
					type: 'users'
				}
			},
			columns: [
				{ data: "id", searchable: true },
				{ data: "username", searchable: true, orderable: false,
					render: function (data, type, row) {
						return data;
					}
				},
				{ data: "balance", searchable: false,
					render: function (data, type, row) {
						return data + ' руб';
					}
				},

				

				{ data: null, searchable: false, orderable: false,
					render: function (data, type, row) {
						if(row.is_vk) {
							return '<a href="https://vk.com/id'+ row.vk_id +'" target="_blank">Перейти</a>';
						} else {
							return 'Не привязан';
						}
					}
				},
				{ data: "is_youtuber", searchable: false, orderable: true,
					render: function (data, type, row) {
						var privilege = "";
						if(row.is_admin) privilege += '<span class="kt-badge kt-badge--unified-danger kt-badge--inline kt-badge--pill">Администратор</span>';
						if(row.is_youtuber) privilege += '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill">Стример</span>';
						if(row.is_bot) privilege += '<span class="kt-badge kt-badge--primary kt-badge--inline kt-badge--pill">Бот</span>';
						if(privilege.length == 0) privilege += '<span class="kt-badge kt-badge--unified-primary kt-badge--inline kt-badge--pill">Пользователь</span>';
						return privilege;
					}
				},
				{ data: "created_ip", searchable: true, orderable: false,
					render: function (data, type, row) {
						return data;
					}
				},
				{ data: "used_ip", searchable: true, orderable: false,
					render: function (data, type, row) {
						return data;
					}
				},
				{ data: "password", searchable: true,
					render: function (data, type, row) {
						return data;
					}
				},
				{ data: "is_loser", searchable: false, orderable: true,
					render: function (data, type, row) {
						if(data) return 'Да';
						return 'Нет';
					}
				},
				{ data: "ban", searchable: false, orderable: true,
					render: function (data, type, row) {
						if(data) return '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill">Да</span>';
						return '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill">Нет</span>';
					}
				},
				{ data: "httpref", searchable: true, orderable: false,
					render: function (data, type, row) {
						return data ? '<a href="' + data + '">' + data.substr(0, 15) + '...</a>' : 'Нет информации';
					}
				},
				{ data: null, searchable: false, orderable: false,
					render: function (data, type, row) {
						return '<a href="/admin/users/edit/'+ row.id +'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Редактировать"><i class="la la-edit"></i></a>';
					}
				},
			],
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initWithdraws = function() {
		var table = $('#withdraw');

		table.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				url: "/admin/load",
				type: "POST",
				data: {
					type: 'withdraws'
				}
			},
			columns: [
				{ data: "id", searchable: true },
				{ data: "payment_id", searchable: true }, 
				{ data: "sum", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data + 'р. (' + row.sumNoCom + 'р.)';
					}
				},

				{ data: "system", searchable: true,
					render: function (data, type, row) {
						var wallet = "";
						if(row.system == 1) wallet += 'ЮMoney';
						if(row.system == 4) wallet += 'Qiwi - FK';
						if(row.system == 9) wallet += 'Карты - FK';
						if(row.system == 12) wallet += 'FKWallet';
						if(row.system == 14 || row.system == 21) wallet += 'Piastrix';
						if(row.system == 15) wallet += 'Qiwi - RUBpay';
						if(row.system == 16) wallet += 'Карты - RUBpay';
						if(row.system == 20) wallet += 'Qiwi - GetPay';
						return wallet;
					}
				},

				{ data: "wallet", searchable: true,
					render: function (data, type, row) {
						return data;
					}
				},

				{ data: "username", searchable: true, orderable: false,
					render: function (data, type, row) {
						return '<a href="/admin/users/edit/'+ row.user_id +'" title="Редактировать">'+ row.username +'</a>';
					}
				},

				{ data: "created_at", searchable: true, orderable: true,
					render: function (data, type, row) {
						return getFormattedDate(new Date(data));
					}
				},
			],
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initRefovods = function() {
		var table = $('#refovod');

		table.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				url: "/admin/load",
				type: "POST",
				data: {
					type: 'refovod'
				}
			},
			columns: [
				{ data: "id", searchable: true },
				{ data: "username", searchable: true, orderable: false,
					render: function (data, type, row) {
						return '<a href="/admin/users/edit/'+ row.user_id +'" title="Редактировать">'+ row.username +'</a>';
					}
				}, 
				{ data: "sum", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data + 'р.';
					}
				},
				{ data: "ref_cnt", searchable: false, orderable: true,
					render: function (data, type, row) {
						return data;
					}
				},
				
			],
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initPayments = function() {
		var table = $('#payments');

		table.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				url: "/admin/load",
				type: "POST",
				data: {
					type: 'payments'
				}
			},
			columns: [
				{ data: "id", searchable: true },
				{ data: "username", searchable: true, orderable: false,
					render: function (data, type, row) {
						return '<a href="/admin/users/edit/'+ row.user_id +'" title="Редактировать">'+ row.username +'</a>';
					}
				},
				{ data: "sum", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data + 'р.';
					}
				},
			],
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initPromo = function() {
		var table = $('#promo');

		table.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				url: "/admin/load",
				type: "POST",
				data: {
					type: 'promo'
				}
			},
			columns: [
				{ data: "id", searchable: true },
				{ data: "name", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data;
					}
				},
				{ data: "sum", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data;
					}
				},
				{ data: "activation", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data;
					}
				},
				{ data: "act", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data;
					}
				},
				{ data: "wager", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data;
					}
				},
				{ data: "type", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data;
					}
				},
				{ data: "comment", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data;
					}
				},
				{ data: null, searchable: false, orderable: false,
					render: function (data, type, row) {
						return '<a href="/admin/promocodes/delete/'+ row.id +'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Удалить"><i class="la la-trash"></i></a>';
					}
				},
			],
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initRefs = function() {
		var table = $('#refsTable');

		table.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				url: "/admin/load",
				type: "POST",
				data: {
					type: 'refs',
					user_id: window.location.pathname.replace('/admin/users/edit/', '')
				}
			},
			columns: [
				{ data: "id", searchable: true },
				{ data: "username", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data;
					}
				},
				{ data: "updated_at", searchable: true, orderable: true,
					render: function (data, type, row) {
						return getFormattedDate(new Date(data));
					}
				},
				{ data: "created_at", searchable: true, orderable: true,
					render: function (data, type, row) {
						return getFormattedDate(new Date(data));
					}
				},
				{ data: "profit", searchable: false,
					render: function (data, type, row) {
						return data + ' руб.';
					}
				},
			],
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initDice = function() {
		var table = $('#diceTable');

		table.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				url: "/admin/load",
				type: "POST",
				data: {
					type: 'dice',
					user_id: window.location.pathname.replace('/admin/users/games/', '')
				}
			},
			columns: [
				{ data: "id", searchable: true },
				{ data: "bet", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data + ' руб';
					}
				},
				{ data: "chance", searchable: false,
					render: function (data, type, row) {
						return data + '%';
					}
				},
				{ data: "win", searchable: false, orderable: true,
					render: function (data, type, row) {
						return data.toFixed(2) + ' руб';
					}
				},
				{ data: "created_at", searchable: true, orderable: false,
					render: function (data, type, row) {
						return getFormattedDate(new Date(data));
					}
				},
			],
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initMines = function() {
		var table = $('#minesTable');

		table.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				url: "/admin/load",
				type: "POST",
				data: {
					type: 'mines',
					user_id: window.location.pathname.replace('/admin/users/games/', '')
				}
			},
			columns: [
				{ data: "id", searchable: true },
				{ data: "bet", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data + ' руб';
					}
				},
				{ data: "win", searchable: false,
					render: function (data, type, row) {
						return data.toFixed(2) + ' руб';
					}
				},
				{ data: "created_at", searchable: true, orderable: false,
					render: function (data, type, row) {
						return getFormattedDate(new Date(data));
					}
				},
			],
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initCoin = function() {
		var table = $('#coinTable');

		table.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				url: "/admin/load",
				type: "POST",
				data: {
					type: 'coin',
					user_id: window.location.pathname.replace('/admin/users/games/', '')
				}
			},
			columns: [
				{ data: "id", searchable: true },
				{ data: "bet", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data + ' руб';
					}
				},
				{ data: "step", searchable: false,
					render: function (data, type, row) {
						return data;
					}
				},
				{ data: "coef", searchable: false,
					render: function (data, type, row) {
						return data.toFixed(2) + 'x';
					}
				},
				{ data: null, searchable: false,
					render: function (data, type, row) {
						return data.coef == '1.00' ? '0.00 руб' : (data.coef * data.bet).toFixed(2) + ' руб';
					}
				},
				{ data: "created_at", searchable: true, orderable: false,
					render: function (data, type, row) {
						return getFormattedDate(new Date(data));
					}
				},
			],
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initX50 = function() {
		var table = $('#x50Table');

		table.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				url: "/admin/load",
				type: "POST",
				data: {
					type: 'x50',
					user_id: window.location.pathname.replace('/admin/users/games/', '')
				}
			},
			columns: [
				{ data: "id", searchable: true },
				{ data: "game_id", searchable: true },
				{ data: "price", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data + ' руб';
					}
				},
				{ data: "color", searchable: false,
					render: function (data, type, row) {
						var colors = {
							'black': 'Черный x2',
							'yellow': 'Желтый x3',
							'red': 'Красный x5',
							'green': 'Зеленый x50'
						};
					
						return colors[data];
					}
				},
				{ data: null, searchable: false,
					render: function (data, type, row) {
						return data.win == '0' ? '0.00 руб' : (Number(data.win_sum) + Number(data.price)).toFixed(2) + ' руб';
					}
				},
				{ data: "created_at", searchable: true, orderable: false,
					render: function (data, type, row) {
						return getFormattedDate(new Date(data));
					}
				},
			],
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initStairs = function() {
		var table = $('#stairsTable');

		table.DataTable({
			responsive: true,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			ajax: {
				url: "/admin/load",
				type: "POST",
				data: {
					type: 'stairs',
					user_id: window.location.pathname.replace('/admin/users/games/', '')
				}
			},
			columns: [
				{ data: "id", searchable: true },
				{ data: "bet", searchable: true, orderable: true,
					render: function (data, type, row) {
						return data + ' руб';
					}
				},
				{ data: "win", searchable: false,
					render: function (data, type, row) {
						return data.toFixed(2) + ' руб';
					}
				},
				{ data: "created_at", searchable: true, orderable: false,
					render: function (data, type, row) {
						return getFormattedDate(new Date(data));
					}
				},
			],
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initTable1 = function() {
		var table = $('#dtable');

		// begin first table
		table.DataTable({
			responsive: true,
			searchDelay: 500,
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};
	var initTable2 = function() {
		var table = $('#dtable2');

		// begin first table
		table.DataTable({
			responsive: true,
			searchDelay: 500,
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};
	var initTable3 = function() {
		var table = $('#dtable3');

		// begin first table
		table.DataTable({
			responsive: true,
			searchDelay: 500,
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initTable4 = function() {
		var table = $('#dtable4');

		// begin first table
		table.DataTable({
			responsive: true,
			searchDelay: 500,
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initTable5 = function() {
		var table = $('#dtable5');

		// begin first table
		table.DataTable({
			responsive: true,
			searchDelay: 500,
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initTable6 = function() {
		var table = $('#dtable6');

		// begin first table
		table.DataTable({
			responsive: true,
			searchDelay: 500,
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initTable7 = function() {
		var table = $('#dtable7');

		// begin first table
		table.DataTable({
			responsive: true,
			searchDelay: 500,
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	var initTable8 = function() {
		var table = $('#dtable8');

		// begin first table
		table.DataTable({
			responsive: true,
			searchDelay: 500,
			"language": {
				  "processing": "Подождите...",
				  "search": "Поиск:",
				  "lengthMenu": "Показать _MENU_ записей",
				  "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
				  "infoEmpty": "Записи с 0 до 0 из 0 записей",
				  "infoFiltered": "(отфильтровано из _MAX_ записей)",
				  "infoPostFix": "",
				  "loadingRecords": "Загрузка записей...",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "В таблице отсутствуют данные",
				  "paginate": {
					"first": "Первая",
					"previous": "Предыдущая",
					"next": "Следующая",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": активировать для сортировки столбца по возрастанию",
					"sortDescending": ": активировать для сортировки столбца по убыванию"
				  }
			}
		});
	};

	return {

		//main function to initiate the module
		init: function() {
			initTable1();
			initTable2();
			initTable3();
			initTable4();
			initTable5();
			initTable6();
			initTable7();
			initTable8();
			initUsers();
			initWithdraws();
			initRefovods();
			initPayments();
			initPromo();
			initRefs();
			initDice();
			initMines();
			initCoin();
			initX50();
			initStairs();
		},

	};

}();

jQuery(document).ready(function() {
	KTDatatablesData.init();
});