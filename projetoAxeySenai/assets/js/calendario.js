        // Variáveis globais
        var startDate, endDate, startDayDate;
        var today = new Date();
        // console.log(today);
        var todayDate = today.getFullYear() + '-' +
            String(today.getMonth() + 1).padStart(2, '0') + '-' +
            String(today.getDate()).padStart(2, '0');
        // console.log(todayDate)
        var currentTime = today.toTimeString().split(' ')[0].substring(0, 5); // Hora atual no formato HH:MM
        // console.log(currentTime)

        // Função para capturar e formatar as datas
        function captureAndFormatDates(info) {
            startDate = new Date(info.startStr);
            endDate = new Date(info.endStr);

            // console.log(startDate);
            // console.log(endDate);

            // Ajustar a data final para o dia correto
            if (endDate > startDate) {
                startDate.setDate(startDate.getDate() + 1);
                // console.log(startDate)
                endDate.setDate(endDate.getDate());
            }

            // Função para formatar a data no formato DD-MM-YYYY
            function formatDateToDDMMYYYY(date) {
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const year = date.getFullYear();
                return `${day}-${month}-${year}`;
            }

            var formattedStartDateBRL = formatDateToDDMMYYYY(startDate);
            var formattedEndDateBRL = formatDateToDDMMYYYY(endDate);

            // Se a data inicial e a data final são iguais, exibir apenas uma data
            var displayDate = (startDate.getTime() === endDate.getTime()) ?
                formattedStartDateBRL :
                formattedStartDateBRL + " - " + formattedEndDateBRL;

            return {
                displayDate: displayDate
            };
        }

        // console.log(startDate);
        // console.log(endDate);
        // console.log(today);

        document.addEventListener('DOMContentLoaded', function() {
            var userState = 0; // Estado do usuário: 0 para editar, 1 para visualizar
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                height: '100%',
                editable: true,
                titleFormat: { // will produce something like "Tuesday, September 18, 2018"
                    month: 'short',
                    year: 'numeric',
                  },
                headerToolbar: {
                    start:'',
                    center: 'title',
                    end:'',
                },
                footerToolbar: {
                    start: 'today',
                    center:'',
                    end: 'prevYear,prev,next,nextYear'
                },
                eventColor: 'green',
                events: [{
                        title: 'All Day Event',
                        start: '2024-08-01'
                    },
                    {
                        title: 'Long Event',
                        start: '2024-08-07',
                        end: '2024-08-10'
                    },
                    {
                        title: 'Conference',
                        start: '2024-08-11',
                        end: '2024-08-13'
                    },
                    {
                        title: 'Meeting',
                        start: '2024-08-12T10:30:00',
                        end: '2024-08-12T12:30:00'
                    },
                    {
                        title: 'Lunch',
                        start: '2024-08-12T12:00:00'
                    },
                    {
                        title: 'Meeting',
                        start: '2024-08-12T14:30:00'
                    },
                    {
                        title: 'Happy Hour',
                        start: '2024-08-12T17:30:00'
                    },
                    {
                        title: 'Dinner',
                        start: '2024-08-12T20:00:00'
                    },
                    {
                        title: 'Birthday Party',
                        start: '2024-08-13T07:00:00'
                    },
                    {
                        title: 'Vacation',
                        start: '2024-08-13',
                        end: '2024-08-17'
                    }
                ],
                selectable: true,
                select: function(info) {
                    var dates = captureAndFormatDates(info);
                    var displayDate = dates.displayDate;

                    // Verificar se o usuário está no modo de edição (0) ou visualização (1)
                    if (userState === 0) {
                        // Definir a data no input do formulário
                        document.getElementById('serviceDate').value = displayDate;

                        // Exibir os campos de hora editáveis e esconder os campos de visualização
                        document.getElementById('timeEditableFields').style.display = 'block';
                        document.getElementById('timeDisplayFields').style.display = 'none';

                        // Exibir o formulário pop-up
                        document.getElementById('popupForm').style.display = 'block';
                    } else if (userState === 1) {
                        Swal.fire({
                            title: 'Detalhes do Serviço',
                            html: `
                    <p><strong>Data:</strong> ${displayDate}</p>
                    <p><strong>Hora Início:</strong> 08:00</p>
                    <p><strong>Hora Fim:</strong> 12:00</p>
                    <p><strong>Título:</strong> Meu Título</p>
                    <p><strong>Descrição:</strong> Minha Descrição</p>
                    `,
                            icon: 'info',
                            confirmButtonText: 'Fechar'
                        });
                    }

                    // console.log(startDate);
                    // console.log(endDate);
                }
            });

            // Inicializar o calendário
            calendar.render();
            // Evento para abrir o calendário no modal
            var showCalendarButton = document.getElementById('show-calendar');
            if (showCalendarButton) {
                showCalendarButton.addEventListener('click', function() {
                    document.getElementById('calendarModal').style.display = 'block';
                    calendar.render();
                });
            }

            // Evento para fechar o modal
            var closeModalButton = document.querySelector('.close');
            if (closeModalButton) {
                closeModalButton.addEventListener('click', function() {
                    document.getElementById('calendarModal').style.display = 'none';
                });
            }

            // Evento para fechar o formulário pop-up
            var closePopupButton = document.querySelector('.close-popup');
            if (closePopupButton) {
                closePopupButton.addEventListener('click', function() {
                    document.getElementById('popupForm').style.display = 'none';
                    document.getElementById("serviceForm").reset();
                });
            }

            // Função de validação do formulário
            var serviceForm = document.getElementById('serviceForm');
            if (serviceForm) {
                serviceForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    var serviceDate = `${startDate} - ${endDate}`;
                    // console.log("Service " + serviceDate);
                    var startTime = document.getElementById('eventHoraInicio').value;
                    var endTime = document.getElementById('eventHoraFim').value;
                    var title = document.getElementById('eventTitle').value;
                    var description = document.getElementById('eventDesc').value;



                    var startDayDate =
                        startDate.getFullYear() + '-' +
                        String(startDate.getMonth() + 1).padStart(2, '0') + '-' +
                        String(startDate.getDate()).padStart(2, '0');

                    var endDayDate =
                        endDate.getFullYear() + '-' +
                        String(endDate.getMonth() + 1).padStart(2, '0') + '-' +
                        String(endDate.getDate()).padStart(2, '0');


                    console.log(startDayDate);
                    console.log(endDayDate);
                    console.log(todayDate);
                    console.log(startTime);
                    console.log(endTime);
                    console.log(currentTime);

                    // Verificar se todos os campos obrigatórios estão preenchidos
                    if (!serviceDate || !startTime || !endTime || !title || !description) {
                        console.log("01");
                        Swal.fire({
                            title: 'Erro',
                            text: 'Todos os campos devem ser preenchidos.',
                            icon: 'error',
                            confirmButtonText: 'Fechar'
                        });
                        return;
                    } else if (endDayDate < startDayDate) {
                        // Verificar se a data inicial é menor que a data final
                        console.log("02");
                        Swal.fire({
                            title: 'Erro',
                            text: 'A data inicial não pode ser maior que a data final.',
                            icon: 'error',
                            confirmButtonText: 'Fechar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // document.getElementById("serviceForm").reset();
                                document.getElementById('popupForm').style.display = 'none'
                            }
                        });
                        return;
                    } else if (startDayDate < todayDate) {
                        //Verifica se data de start é menor a data do que hoje
                        console.log("03");
                        Swal.fire({
                            title: 'Erro',
                            text: 'A data inicial não pode ser menor que a data atual.',
                            icon: 'error',
                            confirmButtonText: 'Fechar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // document.getElementById("serviceForm").reset();
                                document.getElementById('popupForm').style.display = 'none'
                            }
                        });
                        return;
                    } else if (startDayDate === todayDate && endDayDate === todayDate && startTime < currentTime) {
                        // Verifica se dia start e end é igual a hoje e valida hora
                        console.log("04");
                        Swal.fire({
                            title: 'Erro',
                            text: 'A hora inicial não pode ser menor que a hora atual.',
                            icon: 'error',
                            confirmButtonText: 'Fechar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // document.getElementById("serviceForm").reset();
                                document.getElementById('popupForm').style.display = 'none'
                            }
                        });
                        return;
                    } else if (startDayDate === todayDate && endDayDate === todayDate && endTime < startTime) {
                        // Valida de hora de star é menor que hora de end
                        console.log("05");
                        Swal.fire({
                            title: 'Erro',
                            text: 'A hora final não pode ser menor que a hora inicial.',
                            icon: 'error',
                            confirmButtonText: 'Fechar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // document.getElementById("serviceForm").reset();
                                document.getElementById('popupForm').style.display = 'none'
                            }
                        });
                        return;
                    } else if (startDayDate === todayDate && endDayDate > todayDate && startTime < currentTime) {
                        // Verifica se dia start  e valida hora
                        console.log("06");
                        Swal.fire({
                            title: 'Erro',
                            text: 'A hora inicial não pode ser menor que a hora atual.',
                            icon: 'error',
                            confirmButtonText: 'Fechar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // document.getElementById("serviceForm").reset();
                                document.getElementById('popupForm').style.display = 'none'
                            }
                        });
                        return;
                    } else {
                        // Se tudo estiver correto, você pode prosseguir com o envio ou outra lógica
                        Swal.fire({
                            title: 'Sucesso',
                            text: 'Serviço salvo com sucesso.',
                            icon: 'success',
                            confirmButtonText: 'Fechar'
                        });
                    }

                    // Limpar os campos do formulário
                    serviceForm.reset();

                    // Fechar o formulário
                    document.getElementById('popupForm').style.display = 'none';
                });
            }
        });