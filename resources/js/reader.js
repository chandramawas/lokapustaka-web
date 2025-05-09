import ePub from 'epubjs';

document.addEventListener("DOMContentLoaded", function () {    
    const reader = document.getElementById('reader');
    const epubUrl = reader.dataset.epub;
    const book = ePub(epubUrl);
    const loading = document.getElementById('book-loading');
    const savedFlow = localStorage.getItem("readFlow") || "paginated";

    const rendition = book.renderTo("reader", {
        manager: "continuous",
        flow: savedFlow,
        width: "100%",
        height: "100%",
        spread: "none",
    });

    // Next/Prev Page
    const prevButton = document.getElementById("prev-page");
    const nextButton = document.getElementById("next-page");
    const ratingButton = document.getElementById("rating-button");

    function updateNavigationButtons(location) {
        const atStart = location.atStart;
        const atEnd = location.atEnd;

        if (atStart) {
            prevButton.classList.add("opacity-50", "pointer-events-none");
        } else {
            prevButton.classList.remove("opacity-50", "pointer-events-none");
        }

        if (atEnd) {
            ratingButton.classList.remove("hidden");
            nextButton.classList.add("hidden");
        } else {
            nextButton.classList.remove("hidden");
            ratingButton.classList.add("hidden");
        }
    }

    prevButton.addEventListener("click", function () {
        book.rendition.prev();
    });

    nextButton.addEventListener("click", function () {
        book.rendition.next();
    });

    function navButton(show) {
        if (show === "show") {
            nextButton.classList.remove("hidden");
            prevButton.classList.remove("hidden");
        } else {
            nextButton.classList.add("hidden");
            prevButton.classList.add("hidden");
        }
    }

    if (savedFlow === "scrolled") {
        reader.classList.add("overflow-y-auto");
        navButton("hide");
    }
    
    // Tombol flow
    const flowButtons = {
        paginated: document.getElementById("flow-paginated"),
        scrolled: document.getElementById("flow-scrolled"),
    };

    // Set state active
    function setActiveFlowButton(flowType) {
        Object.entries(flowButtons).forEach(([key, button]) => {
            if (key === flowType) {
                button.classList.add("ring", "ring-primary");
            } else {
                button.classList.remove("ring", "ring-primary");
            }
        });
    }

    setActiveFlowButton(savedFlow);

    // Ganti flow
    flowButtons.paginated.addEventListener("click", () => {
        rendition.flow("paginated");
        reader.classList.remove("overflow-y-auto");
        reader.classList.add("overflow-y-hidden");
        localStorage.setItem("readFlow", "paginated");
        setActiveFlowButton("paginated");
        navButton("show");
    });

    flowButtons.scrolled.addEventListener("click", () => {
        rendition.flow("scrolled");
        reader.classList.remove("overflow-y-hidden");
        reader.classList.add("overflow-y-auto");
        localStorage.setItem("readFlow", "scrolled");
        setActiveFlowButton("scrolled");
        navButton("hide");
    });

    // Tampilkan loading
    loading.style.display = 'flex';

    // Pas buku udah ready
    book.ready.then(() => {
        return book.locations.generate(1000);
    }).then(() => {
        rendition.display().then((location) => {
            // Sembunyiin loading
            loading.style.display = 'none';

            //Table of Content
            book.loaded.navigation.then((nav) => {
                const toc = nav.toc;
                const tocListItems = toc.map((chapter) => {
                    return `<li><a href="#" data-href="${chapter.href}" class="toc-link hover:underline">${chapter.label}</a></li>`;
                }).join("<hr>");
            
                const tocContainers = document.querySelectorAll('ul.toc-list');
                tocContainers.forEach(container => {
                    container.innerHTML = tocListItems;
                });
            
                // Klik TOC untuk lompat ke bab
                document.querySelectorAll('.toc-link').forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        const href = e.target.getAttribute('data-href');
                        book.rendition.display(href);
                    });
                });
            });   
        
            // Update progress awal
            updateProgress(location);
        });

        // Event page relocated
        rendition.on("relocated", (location) => {
            updateProgress(location);
            if (savedFlow === 'paginated') {
                updateNavigationButtons(location);
            }
        });
    });

    function updateProgress(location) {
        const progress = book.locations.percentageFromCfi(location.start.cfi);
        const progressBar = document.getElementById('reading-progress-bar');
        const progressText = document.getElementById('reading-progress-text');
        const progressPercent = Math.floor(progress * 100);

        // Tentukan style berdasarkan progress
        const isLowProgress = progressPercent < 50;
        const textClasses = isLowProgress
            ? ['bg-secondary-container', 'text-on-secondary-container']
            : ['bg-primary-container', 'text-on-primary-container'];

        const barClass = isLowProgress ? 'bg-secondary-container' : 'bg-primary-container';

        // Reset kelas
        progressText.classList.remove('bg-primary-container', 'bg-secondary-container', 'text-on-primary-container', 'text-on-secondary-container');
        progressText.classList.add(...textClasses);

        progressBar.classList.remove('bg-primary-container', 'bg-secondary-container');
        progressBar.classList.add(barClass);

        progressBar.style.width = `${progressPercent}%`;
        progressText.textContent = `${progressPercent}%`;
    } 

    // FONT SIZE
    let currentFontSize = parseInt(localStorage.getItem("fontSize")) || 100;
    rendition.themes.fontSize(currentFontSize + "%");

    document.getElementById("increase-font").addEventListener("click", () => {
        if (currentFontSize < 200) { // Maksimum
            currentFontSize += 10;
            rendition.themes.fontSize(currentFontSize + "%");
            localStorage.setItem("fontSize", currentFontSize);
        }
    });

    document.getElementById("decrease-font").addEventListener("click", () => {
        if (currentFontSize > 50) { // Minimum
            currentFontSize -= 10;
            rendition.themes.fontSize(currentFontSize + "%");
            localStorage.setItem("fontSize", currentFontSize);
        }
    });

    // Color Theme
    rendition.themes.register("day", {
        "body": {
          color: "#222", // abu gelap (bukan hitam pekat, biar nggak harsh)
          background: "#ffffff"
        },
        "a": {
          background: "none !important",
          color: "#1a0dab !important" // biru khas link Google
        },
        "a:hover": {
          "text-decoration": "underline !important"
        }
    });
    rendition.themes.register("night", {
        "body": {
          color: "#dbeafe", // biru muda keputihan (soft di mata)
          background: "#0f172a" // navy dark blue (Twilight vibes)
        },
        "a": {
          background: "none !important",
          color: "#60a5fa !important" // biru soft, enak di dark blue
        },
        "a:hover": {
          "text-decoration": "underline !important"
        }
    });
    rendition.themes.register("sepia", {
        "body": {
          color: "#5b4636", // coklat gelap
          background: "#f4ecd8" // krem sepia
        },
        "a": {
          background: "none !important",
          color: "#b35c1e !important" // oranye kecoklatan, selaras sama sepia
        },
        "a:hover": {
          "text-decoration": "underline !important"
        }
    });
    rendition.themes.register("bw", {
        "body": {
          color: "#ffffff", // putih
          background: "#000000" // hitam pekat
        },
        "a": {
          background: "none !important",
          color: "#ffffff !important" // putih juga
        },
        "a:hover": {
          "text-decoration": "underline !important"
        }
    });  
    
    const savedTheme = localStorage.getItem("readTheme") || "day";

    rendition.themes.select(savedTheme);
    updateReaderParentBg(savedTheme);

    const themeButtons = document.querySelectorAll("[data-theme]");

    // SET DEFAULT ACTIVE THEME BUTTON
    themeButtons.forEach(button => {
        const btnTheme = button.getAttribute("data-theme");
        if (btnTheme === savedTheme) {
            button.classList.add("ring", "ring-primary");
        }
    });

    themeButtons.forEach(button => {
        button.addEventListener("click", () => {
            const selectedTheme = button.getAttribute("data-theme");

            localStorage.setItem("readTheme", selectedTheme);
            
            // Apply theme to EPUB reader
            rendition.themes.select(selectedTheme);
            updateReaderParentBg(selectedTheme);
            
            // Update active UI state
            themeButtons.forEach(btn => btn.classList.remove("ring", "ring-primary"));
            button.classList.add("ring", "ring-primary");
        });
    });

    function updateReaderParentBg(theme) {
        const parent = document.getElementById("reader-parent");
    
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
    
        parent.style.backgroundColor = themeBgColors[theme] || "#ffffff";
        parent.style.color = themeColors[theme] || "#000000"
    }

    //FONT STYLE
    const savedFontStyle = localStorage.getItem("fontStyle") || "serif";
    setFont(savedFontStyle);

    function setFont(font) {
        rendition.themes.default({ "body": { "font-family": font } });

        localStorage.setItem("fontStyle", font);
    
        // Remove active class dulu
        document.getElementById('serif').classList.remove('ring', 'ring-primary');
        document.getElementById('sans-serif').classList.remove('ring', 'ring-primary');
    
        // Tambahkan active class ke yang dipilih
        document.getElementById(font).classList.add('ring', 'ring-primary');
    }
    
    document.getElementById('serif').addEventListener('click', () => setFont('serif'));
    document.getElementById('sans-serif').addEventListener('click', () => setFont('sans-serif'));
});