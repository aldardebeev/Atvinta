import './bootstrap';
import hljs from 'highlight.js/lib/core';
import javascript from 'highlight.js/lib/languages/javascript';
document.addEventListener('DOMContentLoaded', () => {
    hljs.registerLanguage('javascript', javascript);
    hljs.initHighlightingOnLoad();
});
