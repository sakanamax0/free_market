const tabLinks = document.querySelectorAll('.tab-link');
const tabPanels = document.querySelectorAll('.tab-panel');

tabLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();

        // 現在のアクティブを解除（存在する場合のみ）
        const currentActiveLink = document.querySelector('.tab-link.active');
        const currentActivePanel = document.querySelector('.tab-panel.active');

        if (currentActiveLink) currentActiveLink.classList.remove('active');
        if (currentActivePanel) currentActivePanel.classList.remove('active');

        // data-tabを取得（クリックされたのが子要素でも対応）
        const targetTab = e.currentTarget.dataset.tab;

        // 新しくアクティブに設定
        link.classList.add('active');
        document.getElementById(targetTab)?.classList.add('active');
    });
});
