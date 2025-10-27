/* Extracted JS for practice stats charts */
(function(){
    // Data from server is expected in window.STATS_PAYLOAD
    if (typeof window.STATS_PAYLOAD === 'undefined') {
        console.warn('STATS_PAYLOAD not found');
        return;
    }

    const rawCourseData = window.STATS_PAYLOAD.courseAverages || [];
    const rawExamData = window.STATS_PAYLOAD.examScores || [];
    const perCourseHistory = window.STATS_PAYLOAD.perCourseHistory || {};

    function colorForIndex(i){
        const palette = ['#3b82f6','#06b6d4','#f97316','#10b981','#ef4444','#8b5cf6','#f59e0b'];
        return palette[i % palette.length];
    }

    // Courses chart
    const courseLabels = rawCourseData.map(c => c.course);
    const courseData = rawCourseData.map(c => c.avg_score);
    const courseColors = rawCourseData.map((c, i) => colorForIndex(i));

    const canvasCourses = document.getElementById('chartCourses');
    function ensureCanvasHeight(canvas, px = 300){
        try{
            // Constrain the parent container and the canvas via CSS to avoid overflow
            canvas.style.maxHeight = px + 'px';
            canvas.style.height = '100%';
            if(canvas.parentElement) {
                // Set a maxHeight and a concrete height so flex layout gives the canvas a size
                canvas.parentElement.style.maxHeight = px + 'px';
                canvas.parentElement.style.height = px + 'px';
                canvas.parentElement.style.display = canvas.parentElement.style.display || 'block';
            }

            // After layout stabilizes, set the canvas drawing buffer height to the computed clientHeight
            setTimeout(()=>{
                try{
                    const h = Math.max(1, Math.round(canvas.clientHeight || px));
                    // assign to canvas.height to ensure Chart.js draws at correct resolution
                    canvas.height = h;
                }catch(e){ /* ignore */ }
            }, 60);
        }catch(e){ /* ignore */ }
    }

    const canvasHeightDefault = 300; // px
    const ctxCourses = canvasCourses ? canvasCourses.getContext('2d') : null;
    if (ctxCourses && canvasCourses) {
        ensureCanvasHeight(canvasCourses, canvasHeightDefault);
        window.chartCourses = new Chart(ctxCourses, {
            type: 'bar',
            data: { labels: courseLabels, datasets: [{ label: 'Promedio (%)', data: courseData, backgroundColor: courseColors, borderColor: courseColors, borderWidth: 1 }] },
            options: {
                responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } },
                plugins: { tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.formattedValue}%` } }, legend: { display: false } },
                onClick(evt, elems) { if (!elems.length) return; const idx = elems[0].index; const courseId = rawCourseData[idx].course_id; openCourseModal(courseId, rawCourseData[idx].course); }
            }
        });
        chartCourses.options.scales.x = { ticks: { maxRotation: 45, minRotation: 30 } };
        // ensure correct initial sizing
        setTimeout(()=>{ try{ chartCourses.resize(); }catch(e){} }, 50);
    }

    // Exams chart
    const examLabels = rawExamData.map(e=> e.date + ' \n' + e.label);
    const examScores = rawExamData.map(e=> e.score);
    const canvasExams = document.getElementById('chartExams');
    const ctxExams = canvasExams ? canvasExams.getContext('2d') : null;
    if (ctxExams && canvasExams) {
        ensureCanvasHeight(canvasExams, canvasHeightDefault);
        window.chartExams = new Chart(ctxExams, { type: 'line', data: { labels: examLabels, datasets: [{ label: 'Puntaje', data: examScores, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', tension: 0.3, pointRadius: 4 }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } }, plugins: { tooltip: { mode: 'index', intersect: false } } } });
        setTimeout(()=>{ try{ chartExams.resize(); }catch(e){} }, 50);
    }

    // UI handlers
    function exportCurrentCoursesCsv(){
        const data = window.chartCourses ? (window.chartCourses.data.labels.map((label,i)=>({ course: label, avg: window.chartCourses.data.datasets[0].data[i] }))) : rawCourseData.map(r=>({ course: r.course, avg: r.avg_score }));
        const rows = [['course','avg_score']];
        data.forEach(r=> rows.push([r.course, r.avg]));
        const csv = rows.map(r=>r.map(c=>`"${String(c).replace(/"/g,'""')}"`).join(',')).join('\n');
        const blob = new Blob([csv],{type:'text/csv'}); const url=URL.createObjectURL(blob); const a=document.createElement('a'); a.href=url; a.download='course_averages.csv'; document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url);
    }

    document.getElementById('exportCoursesCsv')?.addEventListener('click', exportCurrentCoursesCsv);

    document.getElementById('orderSelect')?.addEventListener('change', function(){ const val=this.value; // client-side only: reorder current chart
        if(!window.chartCourses) return; let arr = window.chartCourses.data.labels.map((label,i)=>({ course: label, avg: window.chartCourses.data.datasets[0].data[i] })); if(val==='asc') arr.sort((a,b)=>a.avg-b.avg); if(val==='desc') arr.sort((a,b)=>b.avg-a.avg); window.chartCourses.data.labels = arr.map(x=>x.course); window.chartCourses.data.datasets[0].data = arr.map(x=>x.avg); window.chartCourses.data.datasets[0].backgroundColor = arr.map((x,i)=>colorForIndex(i)); window.chartCourses.update(); try{ window.chartCourses.resize(); }catch(e){} });

    document.getElementById('dateRangeSelect')?.addEventListener('change', function(){ const days = parseInt(this.value,10); // fetch new data via AJAX
        const courseId = document.getElementById('courseFilter')?.value || null;
        fetchStats({ date_range: days, course_id: courseId });
    });

    function openCourseModal(courseId, courseName){ const modalHtml=document.createElement('div'); modalHtml.className='fixed inset-0 bg-black/30 flex items-center justify-center z-50'; const box=document.createElement('div'); box.className='bg-white rounded p-4 w-3/4 max-w-2xl'; box.innerHTML=`<div class="flex justify-between items-center mb-2"><h3 class="font-semibold">Historial: ${courseName}</h3><button id="closeDrill">Cerrar</button></div><div id="drillContent">Cargando...</div>`; modalHtml.appendChild(box); document.body.appendChild(modalHtml); document.getElementById('closeDrill').addEventListener('click', ()=> modalHtml.remove()); const history = perCourseHistory[courseId] || []; if(!history.length){ document.getElementById('drillContent').innerHTML='<p>No hay registros para este curso.</p>'; return; } const canvas = document.createElement('canvas'); canvas.id='drillChart'; canvas.style.width='100%'; canvas.style.maxHeight='300px'; canvas.style.height='100%'; const wrapper = document.createElement('div'); wrapper.style.width='100%'; wrapper.style.height='300px'; wrapper.style.maxHeight='300px'; wrapper.appendChild(canvas); document.getElementById('drillContent').innerHTML=''; document.getElementById('drillContent').appendChild(wrapper); const labels=history.map(h=>h.date); const scores=history.map(h=>h.score); new Chart(canvas.getContext('2d'), { type:'line', data:{ labels, datasets:[{ label: courseName+' - Puntaje', data: scores, borderColor:'#3b82f6' }] }, options:{ responsive:true, maintainAspectRatio:false } }); }

    // Fetch updated stats via AJAX and update charts
    async function fetchStats(params = {}){
        const url = new URL('/practice/stats/data', window.location.origin);
        Object.keys(params).forEach(k=>{ if(params[k] !== null && params[k] !== undefined) url.searchParams.set(k, params[k]); });
        try{
            const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if(!res.ok) throw new Error('Network error');
            const payload = await res.json();
            // update globals
            // update course chart
            const ca = payload.courseAverages || [];
            const labels = ca.map(c=>c.course);
            const data = ca.map(c=>c.avg_score);
            const colors = ca.map((c,i)=>colorForIndex(i));
            if(window.chartCourses){ window.chartCourses.data.labels = labels; window.chartCourses.data.datasets[0].data = data; window.chartCourses.data.datasets[0].backgroundColor = colors; window.chartCourses.update(); try{ window.chartCourses.resize(); }catch(e){} }
            // update exam chart
            const es = payload.examScores || [];
            const examLabels = es.map(e => e.date + ' \n' + e.label);
            const examData = es.map(e => e.score);
            if(window.chartExams){ window.chartExams.data.labels = examLabels; window.chartExams.data.datasets[0].data = examData; window.chartExams.update(); try{ window.chartExams.resize(); }catch(e){} }
            // update perCourseHistory in memory
            window.PER_COURSE_HISTORY = payload.perCourseHistory || {};
            // update summary cards if present
            const overallNode = document.querySelector('[data-overall-avg]'); if(overallNode) overallNode.textContent = (payload.overallAvg ?? 0) + '%';
        }catch(e){ console.error('Error fetching stats', e); }
    }

    // Expose fetchStats globally for manual calls
    window.fetchStats = fetchStats;

    // Initialize PER_COURSE_HISTORY
    window.PER_COURSE_HISTORY = perCourseHistory;

    // Wire course filter
    document.getElementById('courseFilter')?.addEventListener('change', function(){ const courseId = this.value || null; const days = parseInt(document.getElementById('dateRangeSelect')?.value || 0,10); fetchStats({ course_id: courseId, date_range: days }); });

    // Do not automatically overwrite initial payload with an AJAX call on load.
    // Only fetch when the user changes filters. Keep initial charts rendered from window.STATS_PAYLOAD.

    // Helper: debounce
    function debounce(fn, wait){ let t; return function(...args){ clearTimeout(t); t = setTimeout(()=> fn.apply(this,args), wait); }; }

    // Ensure charts resize on window resize
    window.addEventListener('resize', debounce(function(){ try{ if(window.chartCourses) window.chartCourses.resize(); }catch(e){} try{ if(window.chartExams) window.chartExams.resize(); }catch(e){} }, 150));

})();
