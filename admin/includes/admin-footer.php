    </div><!-- /admin-main -->
</div><!-- /admin-content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle (mobile)
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 && sidebar.classList.contains('show') 
                && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    }
    
    // Session timeout uyarısı (25 dakikada)
    setTimeout(function() {
        if (confirm('Oturumunuz yakında sona erecek. Devam etmek istiyor musunuz?')) {
            window.location.reload();
        }
    }, 25 * 60 * 1000);

    // ==================== Real-time Notification Polling ====================
    var lastKnownId = 0;
    var toastContainer = document.getElementById('toastContainer');
    var notifSound = document.getElementById('notifSound');
    var firstLoad = true;

    // İlk yüklemede mevcut en son ID'yi al
    function initPolling() {
        fetch('dashboard.php?ajax=check_new&last_id=0')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success && data.submissions.length > 0) {
                    lastKnownId = data.submissions[0].id;
                }
                firstLoad = false;
                // 15 saniyede bir kontrol et
                setInterval(checkNewSubmissions, 15000);
            })
            .catch(function() {
                firstLoad = false;
                setInterval(checkNewSubmissions, 15000);
            });
    }

    function checkNewSubmissions() {
        fetch('dashboard.php?ajax=check_new&last_id=' + lastKnownId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success && data.submissions.length > 0) {
                    // En yüksek ID'yi güncelle
                    var maxId = lastKnownId;
                    data.submissions.forEach(function(sub) {
                        if (sub.id > maxId) maxId = sub.id;
                    });
                    
                    if (!firstLoad && maxId > lastKnownId) {
                        // Yeni başvurular var - toast göster
                        data.submissions.forEach(function(sub) {
                            if (sub.id > lastKnownId) {
                                showToast(sub);
                            }
                        });
                        // Bildirim sesi çal
                        playNotifSound();
                        // Yeni başvuru sayısını güncelle (varsa badge)
                        updateNewBadge();
                    }
                    lastKnownId = maxId;
                }
            })
            .catch(function() {});
    }

    function showToast(sub) {
        var toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = 
            '<div class="toast-icon"><i class="fas fa-bell"></i></div>' +
            '<div class="toast-body">' +
                '<div class="toast-title">Yeni Başvuru #' + sub.id + '</div>' +
                '<div class="toast-message">' + escapeHtml(sub.type) + ' - ' + escapeHtml(sub.name) + '</div>' +
                '<div class="toast-time"><i class="far fa-clock me-1"></i>' + escapeHtml(sub.time) + '</div>' +
            '</div>' +
            '<button class="toast-close" onclick="this.parentElement.classList.add(\'toast-hide\');setTimeout(function(){this.remove()}.bind(this.parentElement),300)">&times;</button>';
        
        toastContainer.appendChild(toast);

        // 8 saniye sonra otomatik kapat
        setTimeout(function() {
            if (toast.parentElement) {
                toast.classList.add('toast-hide');
                setTimeout(function() { 
                    if (toast.parentElement) toast.remove(); 
                }, 300);
            }
        }, 8000);

        // Maksimum 5 toast göster
        while (toastContainer.children.length > 5) {
            toastContainer.removeChild(toastContainer.firstChild);
        }
    }

    function playNotifSound() {
        try {
            var ctx = new (window.AudioContext || window.webkitAudioContext)();
            // İki tonlu bildirim sesi (ding-dong)
            var notes = [
                { freq: 830, start: 0, dur: 0.15 },
                { freq: 1050, start: 0.18, dur: 0.25 }
            ];
            notes.forEach(function(n) {
                var osc = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.type = 'sine';
                osc.frequency.value = n.freq;
                gain.gain.setValueAtTime(0.3, ctx.currentTime + n.start);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + n.start + n.dur);
                osc.start(ctx.currentTime + n.start);
                osc.stop(ctx.currentTime + n.start + n.dur);
            });
        } catch(e) {}
    }

    function updateNewBadge() {
        // Sidebar'daki yeni başvuru badge'ini güncelle
        var badges = document.querySelectorAll('.badge.bg-danger');
        badges.forEach(function(b) {
            var current = parseInt(b.textContent) || 0;
            b.textContent = current + 1;
        });
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text || ''));
        return div.innerHTML;
    }

    // Polling başlat
    initPolling();
});
</script>
</body>
</html>
