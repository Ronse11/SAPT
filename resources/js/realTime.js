// GETTING THE SUM OF EACH ROWS
function getTotals() {
    $('tr').each(function() {
        let sum = 0;
        let totalCell = $(this).find('.total-cell');
                
        // Iterate over each editable cell in the row
        $(this).find('td[chosen-column="column"]').each(function() {
            // Get the content of the cell and add it to the sum if it's a number
            let cellContent = $(this).text();
            if (!isNaN(cellContent) && cellContent.trim() !== "") {
                sum += parseFloat(cellContent);
            }
        });


    
        // Update the total cell with the calculated sum
        if (totalCell.length) {
            if(sum === 0) {
                sum = '';
            }             
            totalCell.text(sum);         
        }
    });
}


// GETTING THE PS OF EACH ROWS
function getPS() {
    $('tr').each(function() {
        let ps = 0;
        let totalPS = $(this).find('.total-ps');
                
        // Iterate over each editable cell in the row
        $(this).find('td[chosen-ps="column"]').each(function() {
            // Get the content of the cell and add it to the sum if it's a number
            let cellContent = $(this).text();
            // Get the total of the HIGHEST POSSIBLE SCORE
            $('tr').find('td[chosen-total-number="column"]').each(function() {
                let total = $(this).text();
                if (!isNaN(cellContent) && cellContent.trim() !== "") {
                    ps = (parseFloat(cellContent) / parseFloat(total)) * 100;
                }
            });
        });
    
        // Update the total cell with the calculated sum
        if (totalPS.length) {
            const tPs = ps.toString().split('.');
            if(tPs.length === 1 || tPs[1].length === 1) {
                totalPS.text(ps);
            } else {
                totalPS.text(ps.toFixed(2));
            }
        }
    });
}

// GETTING THE WS OF EACH ROWS
function getWS() {
    $('tr').each(function() {
        let ws = 0;
        let totalWS = $(this).find('.total-ws');
                
        // Iterate over each editable cell in the row
        $(this).find('td[chosen-ws="column"]').each(function() {
            // Get the content of the cell and add it to the sum if it's a number
            let cellContent = $(this).text();
            $('tr').find('td[chosen-percent-quiz="column"]').each(function() {
                let wholePercent = $(this).text();
                let percentQuiz = parseFloat(wholePercent) / 100;
                if (!isNaN(cellContent) && cellContent.trim() !== "") {
                    ws = parseFloat(cellContent) * percentQuiz;
                }
            });
        });
    
        // Update the total cell with the calculated sum
        if (totalWS.length) {
            const tWS = ws.toString().split('.');
            if(tWS.length === 1 || tWS[1].length === 1) {
                totalWS.text(ws);
            } else {
                totalWS.text(ws.toFixed(2));
            }
        }
    });
}

// GETTING THE PS OF EXAM ROWS
function getPsExam() {
    $('tr').each(function() {
        let psExam = 0;
        let totalPsExam = $(this).find('.total-ps-exam');
                
        // Iterate over each editable cell in the row
        $(this).find('td[chosen-ps-exam="column"]').each(function() {
            let cellScore = $(this).text();
            // Get the total of the HIGHEST POSSIBLE SCORE
            $('tr').find('td[chosen-total-score="column"]').each(function() {
                let totalScore = $(this).text();
                if (!isNaN(cellScore) && cellScore.trim() !== "") {
                    psExam = (parseFloat(cellScore) / totalScore) * 100;
                }
            });
        });

        // Update the total cell with the calculated sum
        if (totalPsExam.length) {
            const exam = psExam.toString().split('.');
            if(exam.length === 1 || exam[1].length === 1) {
                totalPsExam.text(psExam);
            } else {
                totalPsExam.text(psExam.toFixed(2));
            }
        }
    });
}

// GETTING THE WS OF WRITTEN IN EXAM
function getWsExam() {
    $('tr').each(function() {
        let wsExam = 0;
        let totalWsExam = $(this).find('.total-ws-exam');
                
        // Iterate over each editable cell in the row
        $(this).find('td[chosen-ws-exam="column"]').each(function() {
            let cellContent = $(this).text();
            $('tr').find('td[chosen-percent-exam="column"]').each(function() {
                let wholePercent = $(this).text();
                let percentExam = parseFloat(wholePercent) / 100;

                if (!isNaN(cellContent) && cellContent.trim() !== "") {
                    wsExam = parseFloat(cellContent) * percentExam;
                }
            });
        });
    
        // Update the total cell with the calculated sum
        if (totalWsExam.length) {
            const tWsExam = wsExam.toString().split('.');
            if(tWsExam.length === 1 || tWsExam[1].length === 1) {
                totalWsExam.text(wsExam);
            } else {
                totalWsExam.text(wsExam.toFixed(2));
            }
        }
    });
}

// GETTING THE KNOWLEDGE PERCENT
function getKExam() {
    $('tr').each(function() {
        let knowledge = 0;
        let totalKnowledge = $(this).find('.total-knowledge');
        let cKnowledge;
        let qKnowledge;
        let eKnowledge;

        $('tr').find('td[chosen-percent-knowledge="column"]').each(function() {
            cKnowledge = parseFloat($(this).text());
        });

        $(this).find('td[quiz-ws-knowledge="column"]').each(function() {
            qKnowledge = parseFloat($(this).text());
        });

        $(this).find('td[exam-ws-knowledge="column"]').each(function() {
            eKnowledge = parseFloat($(this).text());
        });

        $(this).find('.total-knowledge').each(function() {    
            if (!isNaN(qKnowledge)) {                
                knowledge = ((qKnowledge + eKnowledge) / 100) * cKnowledge;
            }
        });
        
        // Update the total cell with the calculated sum
        if (totalKnowledge.length) {
            const tKnowledge = knowledge.toString().split('.');
            if(tKnowledge.length === 1 || tKnowledge[1].length === 1) {
                totalKnowledge.text(knowledge);
            } else {
                totalKnowledge.text(knowledge.toFixed(2));
            }
        }
        
    });
}


    
$(document).ready(function() {
    // QUIZZES
    getTotals();
    getPS();
    getWS();

    // WRITTEN  EXAM
    getPsExam();
    getWsExam();

    // KNOWLEDGE PERCENT
    getKExam();
            
    // RECALCULATE THE DATA WHENEVER THE ROWS OF QUIZZES IS CHANGED
    $('td[chosen-column="column"]').on('input', function() {
        // QUIZZES
        getTotals();
        getPS();
        getWS();
    });
    $('td[chosen-percent-quiz="column"]').on('input', function() {
        // QUIZZES
        getWS();
    });

    // RECALCULATE THE DATA WHENEVER THE ROWS OF EXAM SCORE IS CHANGED
    $('td[chosen-ps-exam="column"]').on('input', function() {
        // WRITTEN  EXAM
        getPsExam();
        getWsExam();
        getKExam();
    });
    $('td[chosen-percent-exam="column"]').on('input', function() {
        // WRITTEN  EXAM
        getWsExam();
    });
});


$(document).ready(function() {
    $('.student-cell').hover(function() {
        $(this).toggleClass('active');
    });
});
$(document).ready(function() {
    $('.high-cell').hover(function() {
        $(this).toggleClass('active');
    });
});
$(document).ready(function() {
    $('.percent-cell').hover(function() {
        $(this).toggleClass('active');
    });
});
$(document).ready(function() {
    $('.percent-total-cell').hover(function() {
        $(this).toggleClass('active');
    });
});