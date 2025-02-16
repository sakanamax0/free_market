
// タブのリンクとコンテンツを取得
const tabLinks = document.querySelectorAll('.tab-link');
const tabPanels = document.querySelectorAll('.tab-panel');

// 各タブリンクにクリックイベントを追加
tabLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault(); // リンクのデフォルト動作をキャンセル

        // 現在アクティブなリンクとパネルを非アクティブにする
        document.querySelector('.tab-link.active').classList.remove('active');
        document.querySelector('.tab-panel.active').classList.remove('active');

        // クリックされたリンクと対応するパネルをアクティブにする
        const targetTab = e.target.dataset.tab;
        link.classList.add('active');
        document.getElementById(targetTab).classList.add('active');
    });
});
