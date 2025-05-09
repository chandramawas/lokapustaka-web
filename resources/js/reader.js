import ePub from 'epubjs';

document.addEventListener("DOMContentLoaded", function () {
    const reader = document.getElementById('reader');
    const epubUrl = reader.dataset.epub;
    const book = ePub(epubUrl);
    const loading = document.getElementById('book-loading');
    let savedFlow = localStorage.getItem("readFlow") || "paginated";
    const savedTheme = localStorage.getItem("readTheme") || "day";
    let savedFontSize = parseInt(localStorage.getItem("fontSize")) || 100;
    const savedFontStyle = localStorage.getItem("fontStyle") || "serif";
    const prevButton = document.getElementById("prev-page");
    const nextButton = document.getElementById("next-page");
    const ratingButton = document.getElementById("rating-button");

    const rendition = book.renderTo("reader", {
        manager: "continuous",
        flow: savedFlow,
        width: "100%",
        height: "100%",
        spread: "none",
    });

    initReader();
    setupNavigation(savedFlow);
    setupFlowControls();
    setupFontSize();
    registerTheme();
    setupThemeControls();
    setupFontStyleControls();
    prevButton.addEventListener("click", () => book.rendition.prev());
    nextButton.addEventListener("click", () => book.rendition.next());            

    // Initialization
    function initReader() {
        loading.style.display = 'flex';

        book.ready.then(() => {
            return book.locations.generate(1000);
        }).then(() => {
            rendition.display().then((location) => {
                loading.style.display = 'none';
                updateTableOfContents();
                updateProgress(location);
            });
        });

        rendition.on("relocated", (location) => {
            updateProgress(location);
            if (savedFlow === "paginated") {
                updateNavigationButtons(location);
            }
        });
    }

    function setupNavigation($flow) {
        if ($flow === "paginated") {
            prevButton.classList.remove("hidden");
            nextButton.classList.remove("hidden");
        } else {
            prevButton.classList.add("hidden");
            nextButton.classList.add("hidden");
        }
    }

    function updateNavigationButtons(location) {
        const atStart = location.atStart;
        const atEnd = location.atEnd;

        if (atStart) {
            prevButton.classList.add("opacity-50");
            prevButton.classList.add("pointer-events-none");                   
        } else {
            prevButton.classList.remove("opacity-50");
            prevButton.classList.remove("pointer-events-none");            
        }

        if (atEnd) {
            ratingButton.classList.remove("hidden");
            nextButton.classList.add("hidden");
        } else {
            nextButton.classList.remove("hidden");
            ratingButton.classList.add("hidden");            
        }
    }

    function setupFlowControls() {
        const flowButtons = {
            paginated: document.getElementById("flow-paginated"),
            scrolled: document.getElementById("flow-scrolled"),
        };

        setActiveFlowButton(savedFlow);

        flowButtons.paginated.addEventListener("click", () => changeFlow("paginated"));
        flowButtons.scrolled.addEventListener("click", () => changeFlow("scrolled"));

        function setActiveFlowButton(flowType) {
            Object.entries(flowButtons).forEach(([key, button]) => {
                button.classList.toggle("ring", key === flowType);
                button.classList.toggle("ring-primary", key === flowType);
            });
        }

        function changeFlow(flowType) {
            rendition.flow(flowType);
            localStorage.setItem("readFlow", flowType);
            setActiveFlowButton(flowType);
            setupNavigation(flowType);
            savedFlow = flowType;

            if (flowType === "scrolled") {
                reader.classList.remove("overflow-y-hidden");
                reader.classList.add("overflow-y-auto");
            } else {
                reader.classList.remove("overflow-y-auto");
                reader.classList.add("overflow-y-hidden");
            }
        }
    }

    // FONT SIZE
    function setupFontSize() {
        rendition.themes.fontSize(savedFontSize + "%");

        document.getElementById("increase-font").addEventListener("click", () => {
            if (savedFontSize < 200) { // Maksimum
                savedFontSize += 10;
                rendition.themes.fontSize(savedFontSize + "%");
                localStorage.setItem("fontSize", savedFontSize);
            }
        });

        document.getElementById("decrease-font").addEventListener("click", () => {
            if (savedFontSize > 50) { // Minimum
                savedFontSize -= 10;
                rendition.themes.fontSize(savedFontSize + "%");
                localStorage.setItem("fontSize", savedFontSize);
            }
        });
    }

    function registerTheme() {
        const themes = {
            day: { 
                body: { color: "#222", background: "#ffffff"},
                a: { background: "none !important", color: "#1a0dab !important"},
                "a:hover": { "text-decoration": "underline !important" }
            },
            night: {
                body: { color: "#dbeafe", background: "#0f172a" },
                a: { background: "none !important", color: "#60a5fa !important"},
                "a:hover": { "text-decoration": "underline !important" }
            },
            sepia: {
                body: { color: "#5b4636", background: "#f4ecd8"},
                a: { background: "none !important", color: "#b35c1e !important"},
                "a:hover": { "text-decoration": "underline !important" }
            },
            bw: {
                body: { color: "#ffffff", background: "#000000"},
                a: { background: "none !important", color: "#ffffff !important" },
                "a:hover": { "text-decoration": "underline !important" }
            }
        };

        Object.entries(themes).forEach(([name, theme]) => {
            rendition.themes.register(name, theme);
        });
    }

    function setupThemeControls() {
        const themeButtons = document.querySelectorAll("[data-theme]");
        themeButtons.forEach(button => {
            button.classList.toggle("ring", button.getAttribute("data-theme") === savedTheme);
            button.classList.toggle("ring-primary", button.getAttribute("data-theme") === savedTheme);

            button.addEventListener("click", () => {
                const selectedTheme = button.getAttribute("data-theme");
                localStorage.setItem("readTheme", selectedTheme);
                rendition.themes.select(selectedTheme);
                updateReaderParentBg(selectedTheme);

                themeButtons.forEach(btn => btn.classList.remove("ring", "ring-primary"));
                button.classList.add("ring", "ring-primary");
            });
        });

        rendition.themes.select(savedTheme);
        updateReaderParentBg(savedTheme);

        function updateReaderParentBg(theme) {
            const themeBgColors = {
                day: "#ffffff",
                night: "#0f172a",
                sepia: "#f4ecd8",
                bw: "#000000",
            };

            const themeColors = {
                day: "#222",
                night: "#dbeafe",
                sepia: "#5b4636",
                bw: "#ffffff",
            };

            const parent = document.getElementById("reader-parent");
            parent.style.backgroundColor = themeBgColors[theme] || "#ffffff";
            parent.style.color = themeColors[theme] || "#000000";
        }
    }

    function setupFontStyleControls() {
        setFont(savedFontStyle);

        document.getElementById('serif').addEventListener('click', () => setFont('serif'));
        document.getElementById('sans-serif').addEventListener('click', () => setFont('sans-serif'));

        function setFont(font) {
            rendition.themes.default({ "body": { "font-family": font } });
            localStorage.setItem("fontStyle", font);
            document.getElementById('serif').classList.toggle('ring', font === 'serif');
            document.getElementById('sans-serif').classList.toggle('ring', font === 'sans-serif');
        }
    }

    function updateTableOfContents() {
        book.loaded.navigation.then((nav) => {
            const toc = nav.toc;
            const tocListItems = toc.map((chapter) => {
                return `<li><a href="#" data-href="${chapter.href}" class="toc-link hover:underline">${chapter.label}</a></li>`;
            }).join("<hr>");

            const tocContainers = document.querySelectorAll('ul.toc-list');
            tocContainers.forEach(container => {
                container.innerHTML = tocListItems;
            });

            document.querySelectorAll('.toc-link').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const href = e.target.getAttribute('data-href');
                    book.rendition.display(href);
                });
            });
        });
    }

    function updateProgress(location) {
        if (!location) return;
        const cfi = location.start.cfi;
        const progress = book.locations.percentageFromCfi(cfi);
        const progressBar = document.getElementById('reading-progress-bar');
        const progressText = document.getElementById('reading-progress-text');
        const progressPercent = Math.floor(progress * 100);

        const isLowProgress = progressPercent < 50;
        const textClasses = isLowProgress
            ? ['bg-secondary-container', 'text-on-secondary-container']
            : ['bg-primary-container', 'text-on-primary-container'];

        const barClass = isLowProgress ? 'bg-secondary-container' : 'bg-primary-container';

        progressText.classList.remove('bg-primary-container', 'bg-secondary-container', 'text-on-primary-container', 'text-on-secondary-container');
        progressText.classList.add(...textClasses);

        progressBar.classList.remove('bg-primary-container', 'bg-secondary-container');
        progressBar.classList.add(barClass);

        progressBar.style.width = `${progressPercent}%`;
        progressText.textContent = `${progressPercent}%`;
    }
});