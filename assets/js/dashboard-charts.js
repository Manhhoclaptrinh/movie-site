// Dashboard Chart Functionality
class DashboardCharts {
    constructor() {
        this.modal = null;
        this.chart = null;
        this.initModal();
        this.attachEventListeners();
        
        // Gradient colors
        this.gradients = {
            purple: { start: 'rgba(147, 51, 234, 0.8)', end: 'rgba(79, 70, 229, 0.8)' },
            blue: { start: 'rgba(59, 130, 246, 0.8)', end: 'rgba(37, 99, 235, 0.8)' },
            green: { start: 'rgba(34, 197, 94, 0.8)', end: 'rgba(22, 163, 74, 0.8)' },
            orange: { start: 'rgba(251, 146, 60, 0.8)', end: 'rgba(249, 115, 22, 0.8)' },
            pink: { start: 'rgba(236, 72, 153, 0.8)', end: 'rgba(219, 39, 119, 0.8)' },
            cyan: { start: 'rgba(6, 182, 212, 0.8)', end: 'rgba(14, 165, 233, 0.8)' }
        };
    }

    initModal() {
        // Tạo modal HTML
        const modalHTML = `
            <div id="chartModal" class="chart-modal">
                <div class="chart-modal-content">
                    <div class="chart-modal-header">
                        <h2 id="chartTitle"></h2>
                        <button class="chart-modal-close">&times;</button>
                    </div>
                    <div class="chart-modal-body">
                        <div class="chart-loading" id="chartLoading">
                            <div class="spinner"></div>
                            <p>Đang tải dữ liệu...</p>
                        </div>
                        <canvas id="dashboardChart"></canvas>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        this.modal = document.getElementById('chartModal');
        
        // Đóng modal khi click nút close
        document.querySelector('.chart-modal-close').addEventListener('click', () => {
            this.closeModal();
        });
        
        // Đóng modal khi click bên ngoài
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.closeModal();
            }
        });
    }

    attachEventListeners() {
        // Lấy tất cả stat-card
        const statCards = document.querySelectorAll('.stat-card');
        
        statCards.forEach(card => {
            card.style.cursor = 'pointer';
            card.addEventListener('click', (e) => {
                // Xác định loại thống kê dựa vào class
                if (card.classList.contains('stat-primary')) {
                    this.showChart('movies', '📊 Thống Kê Phim Theo Tháng');
                } else if (card.classList.contains('stat-success')) {
                    this.showChart('views', '🔥 Top Phim Có Lượt Xem Cao Nhất');
                } else if (card.classList.contains('stat-warning')) {
                    this.showChart('categories', '🎬 Phân Bố Phim Theo Thể Loại');
                } else if (card.classList.contains('stat-info')) {
                    this.showChart('episodes', '📺 Top Phim Có Nhiều Tập Nhất');
                }
            });
        });
    }

    async showChart(type, title) {
        document.getElementById('chartTitle').textContent = title;
        this.modal.style.display = 'block';
        
        const loading = document.getElementById('chartLoading');
        const canvas = document.getElementById('dashboardChart');
        
        // Hiển thị loading
        loading.style.display = 'flex';
        canvas.style.display = 'none';
        
        try {
            let data, chartConfig;
            
            switch(type) {
                case 'movies':
                    data = await this.fetchData('movies_by_month');
                    chartConfig = this.createMoviesChart(data, canvas);
                    break;
                    
                case 'views':
                    data = await this.fetchData('views_by_movie');
                    chartConfig = this.createViewsChart(data, canvas);
                    break;
                    
                case 'categories':
                    data = await this.fetchData('movies_by_category');
                    chartConfig = this.createCategoriesChart(data, canvas);
                    break;
                    
                case 'episodes':
                    data = await this.fetchData('episodes_by_movie');
                    chartConfig = this.createEpisodesChart(data, canvas);
                    break;
            }
            
            // Hủy chart cũ nếu có
            if (this.chart) {
                this.chart.destroy();
            }
            
            // Ẩn loading, hiện canvas
            loading.style.display = 'none';
            canvas.style.display = 'block';
            
            // Tạo chart mới
            const ctx = canvas.getContext('2d');
            this.chart = new Chart(ctx, chartConfig);
            
        } catch (error) {
            console.error('Error loading chart:', error);
            loading.innerHTML = '<p style="color: #ef4444;">❌ Có lỗi khi tải dữ liệu</p>';
        }
    }

    async fetchData(type) {
        const response = await fetch(`dashboard_charts_api.php?type=${type}`);
        if (!response.ok) {
            throw new Error('Failed to fetch data');
        }
        return await response.json();
    }
    
    createGradient(ctx, colorScheme) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, colorScheme.start);
        gradient.addColorStop(1, colorScheme.end);
        return gradient;
    }

    createMoviesChart(data, canvas) {
        const labels = data.map(item => {
            const [year, month] = item.month.split('-');
            const monthNames = ['', 'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 
                              'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
            return monthNames[parseInt(month)];
        });
        const values = data.map(item => parseInt(item.total));
        
        const ctx = canvas.getContext('2d');
        
        // Tạo nhiều gradient cho mỗi cột
        const gradients = values.map((_, index) => {
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(139, 92, 246, 1)');
            gradient.addColorStop(0.5, 'rgba(124, 58, 237, 0.9)');
            gradient.addColorStop(1, 'rgba(109, 40, 217, 0.8)');
            return gradient;
        });

        return {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Số phim mới',
                    data: values,
                    backgroundColor: gradients,
                    borderRadius: 12,
                    borderWidth: 0,
                    borderSkipped: false,
                    hoverBackgroundColor: 'rgba(124, 58, 237, 1)',
                    barThickness: 60
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart',
                    onComplete: function() {
                        // Vẽ số liệu lên cột sau khi animation hoàn thành
                        const chart = this;
                        const ctx = chart.ctx;
                        ctx.font = 'bold 14px Inter, sans-serif';
                        ctx.fillStyle = '#ffffff';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        
                        chart.data.datasets.forEach((dataset, i) => {
                            const meta = chart.getDatasetMeta(i);
                            meta.data.forEach((bar, index) => {
                                const data = dataset.data[index];
                                if (data > 0) {
                                    ctx.fillText(data, bar.x, bar.y - 8);
                                }
                            });
                        });
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        padding: 16,
                        cornerRadius: 10,
                        titleFont: { size: 15, weight: 'bold', family: 'Inter' },
                        bodyFont: { size: 14, family: 'Inter' },
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                return '📊 ' + context.parsed.y + ' phim mới';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: Math.max(...values) + 2,
                        ticks: {
                            stepSize: 1,
                            font: { size: 13, weight: '500' },
                            color: '#64748b',
                            padding: 8
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.08)',
                            drawBorder: false,
                            lineWidth: 1
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 13, weight: '600' },
                            color: '#475569',
                            padding: 10
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 30,
                        bottom: 10
                    }
                }
            }
        };
    }

    createViewsChart(data, canvas) {
        const labels = data.map(item => {
            const title = item.title;
            return title.length > 25 ? title.substring(0, 25) + '...' : title;
        });
        const values = data.map(item => parseInt(item.views));
        
        const ctx = canvas.getContext('2d');
        
        // Tạo gradient đẹp cho mỗi bar với màu khác nhau
        const colorSchemes = [
            ['rgba(16, 185, 129, 1)', 'rgba(5, 150, 105, 0.9)'],
            ['rgba(34, 197, 94, 1)', 'rgba(22, 163, 74, 0.9)'],
            ['rgba(59, 130, 246, 1)', 'rgba(37, 99, 235, 0.9)'],
            ['rgba(6, 182, 212, 1)', 'rgba(14, 165, 233, 0.9)'],
            ['rgba(139, 92, 246, 1)', 'rgba(124, 58, 237, 0.9)'],
            ['rgba(236, 72, 153, 1)', 'rgba(219, 39, 119, 0.9)'],
            ['rgba(251, 146, 60, 1)', 'rgba(249, 115, 22, 0.9)'],
            ['rgba(234, 179, 8, 1)', 'rgba(202, 138, 4, 0.9)'],
            ['rgba(20, 184, 166, 1)', 'rgba(13, 148, 136, 0.9)'],
            ['rgba(99, 102, 241, 1)', 'rgba(79, 70, 229, 0.9)']
        ];
        
        const gradients = values.map((_, index) => {
            const gradient = ctx.createLinearGradient(0, 0, 600, 0);
            const colors = colorSchemes[index % colorSchemes.length];
            gradient.addColorStop(0, colors[0]);
            gradient.addColorStop(1, colors[1]);
            return gradient;
        });

        return {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Lượt xem',
                    data: values,
                    backgroundColor: gradients,
                    borderRadius: 10,
                    borderWidth: 0,
                    borderSkipped: false,
                    barThickness: 35
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: true,
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart',
                    onComplete: function() {
                        const chart = this;
                        const ctx = chart.ctx;
                        ctx.font = 'bold 12px Inter, sans-serif';
                        ctx.fillStyle = '#ffffff';
                        ctx.textAlign = 'right';
                        ctx.textBaseline = 'middle';
                        
                        chart.data.datasets.forEach((dataset, i) => {
                            const meta = chart.getDatasetMeta(i);
                            meta.data.forEach((bar, index) => {
                                const data = dataset.data[index];
                                if (data > 0) {
                                    const formatted = data >= 1000 ? (data/1000).toFixed(1) + 'k' : data;
                                    ctx.fillText(formatted, bar.x - 8, bar.y);
                                }
                            });
                        });
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        padding: 16,
                        cornerRadius: 10,
                        titleFont: { size: 14, weight: 'bold', family: 'Inter' },
                        bodyFont: { size: 13, family: 'Inter' },
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return data[context[0].dataIndex].title;
                            },
                            label: function(context) {
                                return '👁️ ' + context.parsed.x.toLocaleString('vi-VN') + ' lượt xem';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            font: { size: 12, weight: '500' },
                            color: '#64748b',
                            padding: 8,
                            callback: function(value) {
                                return value >= 1000 ? (value/1000).toFixed(1) + 'k' : value;
                            }
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.08)',
                            drawBorder: false,
                            lineWidth: 1
                        }
                    },
                    y: {
                        ticks: {
                            font: { size: 12, weight: '600' },
                            color: '#475569',
                            padding: 12
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                },
                layout: {
                    padding: {
                        right: 50,
                        left: 10
                    }
                }
            }
        };
    }

    createCategoriesChart(data, canvas) {
        const labels = data.map(item => item.name);
        const values = data.map(item => parseInt(item.total));

        // Bảng màu gradient đẹp và đa dạng
        const colors = [
            'rgba(139, 92, 246, 1)',   // Purple
            'rgba(59, 130, 246, 1)',   // Blue
            'rgba(16, 185, 129, 1)',   // Green
            'rgba(251, 146, 60, 1)',   // Orange
            'rgba(236, 72, 153, 1)',   // Pink
            'rgba(6, 182, 212, 1)',    // Cyan
            'rgba(124, 58, 237, 1)',   // Deep Purple
            'rgba(234, 179, 8, 1)',    // Yellow
            'rgba(239, 68, 68, 1)',    // Red
            'rgba(14, 165, 233, 1)',   // Light Blue
            'rgba(168, 85, 247, 1)',   // Violet
            'rgba(34, 197, 94, 1)'     // Emerald
        ];
        
        const hoverColors = [
            'rgba(139, 92, 246, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(16, 185, 129, 0.8)',
            'rgba(251, 146, 60, 0.8)',
            'rgba(236, 72, 153, 0.8)',
            'rgba(6, 182, 212, 0.8)',
            'rgba(124, 58, 237, 0.8)',
            'rgba(234, 179, 8, 0.8)',
            'rgba(239, 68, 68, 0.8)',
            'rgba(14, 165, 233, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(34, 197, 94, 0.8)'
        ];

        return {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors.slice(0, labels.length),
                    hoverBackgroundColor: hoverColors.slice(0, labels.length),
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverBorderWidth: 5,
                    hoverBorderColor: '#ffffff',
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            padding: 18,
                            font: { size: 13, weight: '600', family: 'Inter' },
                            color: '#334155',
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 12,
                            boxHeight: 12,
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        const percent = ((value / total) * 100).toFixed(1);
                                        return {
                                            text: `${label} (${value} - ${percent}%)`,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        padding: 16,
                        cornerRadius: 10,
                        titleFont: { size: 15, weight: 'bold', family: 'Inter' },
                        bodyFont: { size: 14, family: 'Inter' },
                        displayColors: true,
                        boxWidth: 15,
                        boxHeight: 15,
                        boxPadding: 8,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return ` ${label}: ${value} phim (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '68%',
                radius: '90%'
            }
        };
    }

    createEpisodesChart(data, canvas) {
        const labels = data.map(item => {
            const title = item.title;
            return title.length > 20 ? title.substring(0, 20) + '...' : title;
        });
        const values = data.map(item => parseInt(item.total_episodes));
        
        const ctx = canvas.getContext('2d');
        
        // Gradient xanh dương đẹp
        const gradients = values.map((_, index) => {
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 1)');
            gradient.addColorStop(0.5, 'rgba(37, 99, 235, 0.95)');
            gradient.addColorStop(1, 'rgba(29, 78, 216, 0.9)');
            return gradient;
        });

        return {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Số tập phim',
                    data: values,
                    backgroundColor: gradients,
                    borderRadius: 12,
                    borderWidth: 0,
                    borderSkipped: false,
                    hoverBackgroundColor: 'rgba(37, 99, 235, 1)',
                    barThickness: 55
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart',
                    onComplete: function() {
                        const chart = this;
                        const ctx = chart.ctx;
                        ctx.font = 'bold 13px Inter, sans-serif';
                        ctx.fillStyle = '#ffffff';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        
                        chart.data.datasets.forEach((dataset, i) => {
                            const meta = chart.getDatasetMeta(i);
                            meta.data.forEach((bar, index) => {
                                const data = dataset.data[index];
                                if (data > 0) {
                                    ctx.fillText(data + ' tập', bar.x, bar.y - 8);
                                }
                            });
                        });
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        padding: 16,
                        cornerRadius: 10,
                        titleFont: { size: 14, weight: 'bold', family: 'Inter' },
                        bodyFont: { size: 13, family: 'Inter' },
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return data[context[0].dataIndex].title;
                            },
                            label: function(context) {
                                return '📺 ' + context.parsed.y + ' tập phim';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5,
                            font: { size: 13, weight: '500' },
                            color: '#64748b',
                            padding: 8
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.08)',
                            drawBorder: false,
                            lineWidth: 1
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11, weight: '600' },
                            color: '#475569',
                            maxRotation: 45,
                            minRotation: 45,
                            padding: 8
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 35,
                        bottom: 10
                    }
                }
            }
        };
    }

    closeModal() {
        this.modal.style.display = 'none';
        if (this.chart) {
            this.chart.destroy();
            this.chart = null;
        }
    }
}

// Khởi tạo khi DOM đã load
document.addEventListener('DOMContentLoaded', function() {
    new DashboardCharts();
});
