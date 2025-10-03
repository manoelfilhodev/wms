@extends('layouts.tv')

@section('content')
<style>
    html, body {
        margin: 0;
        padding: 0;
        background-color: #121212;
        color: #ffffff;
        font-family: sans-serif;
        overflow: hidden;
        height: 100vh;
    }

    #titulo-tv {
        height: 10vh;
        background: #000;
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .header-container {
        width: 100%;
        max-width: 100vw;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 40px;
    }

    .title-text {
        font-size: 2rem;
        font-weight: bold;
        text-align: center;
        flex: 1;
    }

    .logo {
        height: 45px;
        max-width: 150px;
        object-fit: contain;
    }

    .logo-dexco {
        filter: brightness(0) invert(1);
    }

    #carousel {
        height: 90vh;
    }

    .slide {
        display: none;
        height: 100%;
        padding: 0;
        box-sizing: border-box;
        text-align: center;
        
    }

    .slide.active {
        display: block;
    }

    .slide h4 {
        font-size: 1.5rem;
        margin: 5px 0;
        color: #ffffff;
    }

    canvas {
        width: 95vw !important;
        height: 75vh !important;
    }

    .miniatura {
    width: 30vw !important;
    height: 30vh !important;
    margin: 1rem;
    background: #1e1e1e;
    border-radius: 10px;
    padding: 0.5rem;
}

   .grid-miniaturas {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: flex-start;
}

    .fullscreen-btn {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 9999;
        background: #27ae60;
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
    }
    
    .card-slide {
    background: #1e1e1e;
    border-radius: 15px;
    padding: 20px;
    margin: 30px auto;
    max-width: 95vw;
    box-shadow: 0 0 15px rgba(0,0,0,0.5);
}

.slide-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 2.5rem;
    background-color: rgba(0, 0, 0, 0.5);
    border: none;
    color: white;
    padding: 10px 20px;
    cursor: pointer;
    z-index: 1000;
    border-radius: 8px;
    user-select: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

#carousel:hover .slide-nav {
    opacity: 1;
}

#prev-slide { left: 10px; }
#next-slide { right: 10px; }
</style>

<!--<button class="fullscreen-btn d-none" onclick="ativaFullscreen()">Tela Cheia</button>-->

<div id="titulo-tv">
    <div class="header-container">
        <img src="https://www.grupotpc.com/wp-content/uploads/2024/12/350x100px-logo-tpc.png" alt="TPC" class="logo">
        <span class="title-text">Painel de Controle Operacional</span>
        <img src="https://www.dex.co/wp-content/themes/dexco/assets/images/logos/logo.svg" alt="Dexco" class="logo logo-dexco">
    </div>
</div>

<div id="carousel">
    <!-- Slide inicial: Miniaturas -->
    <div class="slide active">
        <h4>Visão Geral da Operação</h4>
        <div class="grid-miniaturas">
            <div>
                <h4>Produção de Kits</h4>
                <canvas id="miniKits" class="miniatura"></canvas>
            </div>
            <div>
                <h4>Top 5 Separação</h4>
                <canvas id="miniTopSeparacao" class="miniatura"></canvas>
            </div>
            <div>
                <h4>Top 5 Armazenagem</h4>
                <canvas id="miniTopArmazenagem" class="miniatura"></canvas>
            </div>
            <div>
                <h4>Paletes Contados</h4>
                <canvas id="miniPaletesMes" class="miniatura"></canvas>
            </div>
            <div>
                <h4>Separações no Mês</h4>
                <canvas id="miniSeparacoesMes" class="miniatura"></canvas>
            </div>
            <div>
                <h4>Armazenagens no Mês</h4>
                <canvas id="miniArmazenagensMes" class="miniatura"></canvas>
            </div>
        </div>

    </div>

    <!-- Slides originais -->
    <div class="slide">
         <div class="card-slide">
             <h4>Produção de Kits - Hoje</h4>
             <canvas id="chartKits"></canvas>
         </div>
     </div>
    <div class="slide">
        <div class="card-slide">
            <h4>Produção de Kits - Mês Atual</h4>
            <canvas id="chartKitsMensal"></canvas>
        </div>
    </div>

     <div class="slide">
         <div class="card-slide">
             <h4>Top 5 Separação - Últimos 7 dias</h4>
             <canvas id="chartTopSeparacao"></canvas>
         </div>
     </div>
     <div class="slide">
         <div class="card-slide">
             <h4>Top 5 Armazenagem - Últimos 7 dias</h4>
             <canvas id="chartTopArmazenagem"></canvas>
         </div>
     </div>
     <div class="slide">
         <div class="card-slide">
             <h4>Paletes Contados no Mês</h4>
             <canvas id="chartPaletesMes"></canvas>
         </div>
     </div>
     <div class="slide">
         <div class="card-slide">
             <h4>Separações no Mês</h4>
             <canvas id="chartSeparacoesMes"></canvas>
         </div>
     </div>
     <div class="slide">
         <div class="card-slide">
             <h4>Armazenagens no Mês</h4>
             <canvas id="chartArmazenagensMes"></canvas>
         </div>
     </div>
     <button id="prev-slide" class="slide-nav">&#8249;</button>
<button id="next-slide" class="slide-nav">&#8250;</button>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    function ativaFullscreen() {
        const elem = document.documentElement;
        if (elem.requestFullscreen) {
            elem.requestFullscreen().catch(err => console.warn('Erro ao tentar fullscreen:', err));
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const topSeparacao = @json($topSeparacao);
        const topArmazenagem = @json($topArmazenagem);
        const dias = @json($dias);
        const separacaoMes = @json($separacaoMes);
        const armazenagemMes = @json($armazenagemMes);
        const paletesMes = @json($paletesMes);
        const kitsHoje = @json($kitsHoje);

        const optionsMini = {
            responsive: true,
            plugins: {
                legend: { display: false },
                datalabels: { display: false }
            },
            scales: {
                x: { ticks: { color: '#fff' } },
                y: { ticks: { color: '#fff' }, beginAtZero: true }
            }
        };

        const optionsFull = {
            responsive: true,
            plugins: {
                legend: { labels: { color: '#fff' } },
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold', size: 16 },
                    anchor: 'end',
                    align: 'start',
                    formatter: value => value,
                    offset: 4,
                    display: ctx => typeof ctx.dataset.data[ctx.dataIndex] !== 'undefined',
                            formatter: (value, ctx) => {
                                if (ctx.dataset.type === 'line') return value + '%';
                                return value;
                            },
                }
            },
            scales: {
                x: { ticks: { color: '#fff' } },
                y: { ticks: { color: '#fff' }, beginAtZero: true }
            }
        };

        const renderChart = (id, labels, data, bgColor, options, type = 'bar') => {
            new Chart(document.getElementById(id), {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: '',
                        data: data,
                        backgroundColor: bgColor
                    }]
                },
                options: options,
                plugins: [ChartDataLabels]
            });
        };

        renderChart('chartTopSeparacao', topSeparacao.map(i => i.nome), topSeparacao.map(i => i.total), '#2ecc71', optionsFull);
        renderChart('miniTopSeparacao', topSeparacao.map(i => i.nome), topSeparacao.map(i => i.total), '#2ecc71', optionsMini);

        renderChart('chartTopArmazenagem', topArmazenagem.map(i => i.nome), topArmazenagem.map(i => i.total), '#3498db', optionsFull);
        renderChart('miniTopArmazenagem', topArmazenagem.map(i => i.nome), topArmazenagem.map(i => i.total), '#3498db', optionsMini);

        renderChart('chartSeparacoesMes', dias, separacaoMes, '#1abc9c', optionsFull);
        renderChart('miniSeparacoesMes', dias, separacaoMes, '#1abc9c', optionsMini);

        renderChart('chartArmazenagensMes', dias, armazenagemMes, '#9b59b6', optionsFull);
        renderChart('miniArmazenagensMes', dias, armazenagemMes, '#9b59b6', optionsMini);

        renderChart('chartPaletesMes', dias, paletesMes, '#2980b9', optionsFull);
        renderChart('miniPaletesMes', dias, paletesMes, '#2980b9', optionsMini);

        const kitLabels = Object.keys(kitsHoje).filter(k => k !== 'TOTAL');

        if (kitLabels.length > 0) {
            const datasetsFull = [
                {
                    type: 'bar',
                    label: 'Programado',
                    data: kitLabels.map(k => kitsHoje[k].programado),
                    backgroundColor: '#2980b9',
                    yAxisID: 'y',
                },
                {
                    type: 'bar',
                    label: 'Produzido',
                    data: kitLabels.map(k => kitsHoje[k].produzido),
                    backgroundColor: '#e67e22',
                    yAxisID: 'y',
                },
                {
                    type: 'line',
                    label: '% Execução',
                    data: kitLabels.map(k => {
                        const kit = kitsHoje[k];
                        if (kit.programado === 0) return 0;
                        return ((kit.produzido / kit.programado) * 100).toFixed(1);
                    }),
                    borderColor: '#27ae60',
                    backgroundColor: '#27ae60',
                    yAxisID: 'y1',
                    tension: 0.3,
                    datalabels: {
                        formatter: value => value + '%',
                        anchor: 'end',
                        align: 'top',
                        color: '#fff'
                    }
                }
            ];

            new Chart(document.getElementById('chartKits'), {
                data: { labels: kitLabels, datasets: datasetsFull },
                options: {
                    responsive: true,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { labels: { color: '#fff' } },
                        datalabels: {
                            color: '#fff',
                            font: { weight: 'bold', size: 14 },
                            anchor: 'end',       // <- topo interno
                            align: 'top',        // <- topo interno (não "start" ou "center")
                            display: ctx => typeof ctx.dataset.data[ctx.dataIndex] !== 'undefined',
                            formatter: (value, ctx) => {
                                if (ctx.dataset.type === 'line') return value + '%';
                                return value;
                            },
                            padding: { top: 6 }
                        }
                    },
                    scales: {
                        x: { ticks: { color: '#fff' } },
                        y: {
                            position: 'left',
                            title: { display: true, text: 'Quantidade', color: '#fff' },
                            ticks: { color: '#fff' },
                            beginAtZero: true,
                            suggestedMax: 1.2 * Math.max(...kitLabels.map(k => kitsHoje[k].programado))
                        },
                        y1: {
                            position: 'right',
                            title: { display: true, text: '% Execução', color: '#fff' },
                            ticks: {
                                color: '#fff',
                                callback: val => val + '%'
                            },
                            beginAtZero: true,
                            suggestedMax: 110,
                            grid: { drawOnChartArea: false }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            if (kitLabels.length > 0) {
    new Chart(document.getElementById('miniKits'), {
        data: {
            labels: kitLabels,
            datasets: [
                {
                    type: 'bar',
                    label: 'Programado',
                    data: kitLabels.map(k => kitsHoje[k].programado),
                    backgroundColor: '#e67e22',
                    yAxisID: 'y',
                },
                {
                    type: 'bar',
                    label: 'Produzido',
                    data: kitLabels.map(k => kitsHoje[k].produzido),
                    backgroundColor: '#2980b9',
                    yAxisID: 'y',
                },
                {
                    type: 'line',
                    label: '% Execução',
                    data: kitLabels.map(k => {
                        const kit = kitsHoje[k];
                        if (kit.programado === 0) return 0;
                        return ((kit.produzido / kit.programado) * 100).toFixed(1);
                    }),
                    borderColor: '#27ae60',
                    backgroundColor: '#27ae60',
                    yAxisID: 'y1',
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                datalabels: { display: false }
            },
            scales: {
                x: { ticks: { color: '#fff' } },
                y: {
                    position: 'left',
                    ticks: { color: '#fff' },
                    beginAtZero: true
                },
                y1: {
                    position: 'right',
                    ticks: {
                        color: '#fff',
                        callback: val => val + '%'
                    },
                    beginAtZero: true,
                    max: 100,
                    grid: { drawOnChartArea: false }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
}

        }

        // Carrossel
        const slides = document.querySelectorAll('.slide');
let index = 0;

const showSlide = (i) => {
    slides.forEach(slide => slide.classList.remove('active'));
    slides[i].classList.add('active');
};

const nextSlide = () => {
    index = (index + 1) % slides.length;
    showSlide(index);
};

const prevSlide = () => {
    index = (index - 1 + slides.length) % slides.length;
    showSlide(index);
};

document.getElementById('next-slide').addEventListener('click', nextSlide);
document.getElementById('prev-slide').addEventListener('click', prevSlide);

// auto slide
setInterval(nextSlide, 60000);
    });
    
    const kitsMensal = @json($kitsMensal);

const diasMes = kitsMensal.map(d => d.dia);
const programadoMes = kitsMensal.map(d => d.programado);
const produzidoMes = kitsMensal.map(d => d.produzido);

new Chart(document.getElementById('chartKitsMensal'), {
    type: 'bar',
    data: {
        labels: diasMes,
        datasets: [
            {
                label: 'Programado',
                data: programadoMes,
                backgroundColor: '#f39c12',
            },
            {
                label: 'Produzido',
                data: produzidoMes,
                backgroundColor: '#27ae60',
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Produção de Kits no Mês Atual',
                color: '#fff'
            },
            legend: {
                labels: {
                    color: '#fff'
                }
            },
            datalabels: {
                color: '#fff',
                anchor: 'end',
                align: 'top',
                formatter: Math.round,
                font: {
                    weight: 'bold'
                }
            }
        },
        scales: {
            x: {
                ticks: { color: '#fff' }
            },
            y: {
                beginAtZero: true,
                ticks: { color: '#fff' },
                title: {
                    display: true,
                    text: 'Quantidade',
                    color: '#fff'
                }
            }
        }
    },
    plugins: [ChartDataLabels]
});


</script>
<script>
    function recarregarPagina() {
          location.reload();
        }
        
        // Define o intervalo para 5 minutos (5 * 60 * 1000 = 300000 milissegundos)
        setInterval(recarregarPagina, 300000);
</script>

@endsection
