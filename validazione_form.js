function validateForm(event) {
    event.preventDefault();
    const sport = document.getElementById('sport').value;
    const data = document.getElementById('data').value;
    let isValid = true;
    let errorMessages = [];

    // Validate date
    if (!data) {
        errorMessages.push("La data è obbligatoria");
        isValid = false;
    }

    // Sport-specific validations
    switch(sport) {
        case 'basket':
            isValid = validateBasket(errorMessages);
            break;
        case 'calcio':
            isValid = validateCalcio(errorMessages);
            break;
        case 'tennis':
            isValid = validateTennis(errorMessages);
            break;
        default:
            errorMessages.push("Seleziona uno sport");
            isValid = false;
    }

    // Display errors or submit form
    if (!isValid) {
        showErrors(errorMessages);
    } else {
        document.querySelector('form').submit();
    }
}

function validateBasket(errorMessages) {
    let isValid = true;
    
    // Minutes validation
    const minuti = parseInt(document.getElementById('minuti_basket').value);
    if (minuti < 0 || minuti > 48) {
        errorMessages.push("I minuti giocati devono essere tra 0 e 48");
        isValid = false;
    }

    // Shots validation
    const tiriTentati = parseInt(document.getElementById('tiri_t').value);
    const tiriRealizzati = parseInt(document.getElementById('makes').value);
    if (tiriRealizzati > tiriTentati) {
        errorMessages.push("I tiri realizzati non possono essere maggiori dei tiri tentati");
        isValid = false;
    }

    // Three pointers validation
    const tiri3Tentati = parseInt(document.getElementById('tiri3').value);
    const tiri3Realizzati = parseInt(document.getElementById('makes3').value);
    if (tiri3Realizzati > tiri3Tentati) {
        errorMessages.push("I tiri da 3 realizzati non possono essere maggiori dei tiri tentati");
        isValid = false;
    }

    // Free throws validation
    const ftTentati = parseInt(document.getElementById('fta').value);
    const ftRealizzati = parseInt(document.getElementById('ft').value);
    if (ftRealizzati > ftTentati) {
        errorMessages.push("I tiri liberi realizzati non possono essere maggiori dei tiri tentati");
        isValid = false;
    }

    // Rebounds validation
    const rimbalziTotali = parseInt(document.getElementById('rimbalzi').value);
    const rimbalziOff = parseInt(document.getElementById('rimbalzi_offensivi').value);
    const rimbalziDif = parseInt(document.getElementById('rimbalzi_difensivi').value);
    if (rimbalziOff + rimbalziDif !== rimbalziTotali) {
        errorMessages.push("La somma dei rimbalzi offensivi e difensivi deve essere uguale al totale");
        isValid = false;
    }

    return isValid;
}

function validateCalcio(errorMessages) {
    let isValid = true;

    // Minutes validation
    const minuti = parseInt(document.getElementById('minuti_calcio').value);
    if (minuti < 0 || minuti > 90) {
        errorMessages.push("I minuti giocati devono essere tra 0 e 90");
        isValid = false;
    }

    // Shots validation
    const tiri = parseInt(document.getElementById('tiri').value);
    const tiriPorta = parseInt(document.getElementById('tiri_in_porta').value);
    const gol = parseInt(document.getElementById('gol').value);
    if (tiriPorta > tiri) {
        errorMessages.push("I tiri in porta non possono essere maggiori del totale dei tiri");
        isValid = false;
    }
    if (gol > tiriPorta) {
        errorMessages.push("I gol non possono essere maggiori dei tiri in porta");
        isValid = false;
    }

    // Passes validation
    const passaggiTentati = parseInt(document.getElementById('passaggi_tentati').value);
    const passaggiRiusciti = parseInt(document.getElementById('passaggi_riusciti').value);
    if (passaggiRiusciti > passaggiTentati) {
        errorMessages.push("I passaggi riusciti non possono essere maggiori dei passaggi tentati");
        isValid = false;
    }

    // Dribbling validation
    const dribblingTentati = parseInt(document.getElementById('dribbling_tentati').value);
    const dribblingRiusciti = parseInt(document.getElementById('dribbling_riusciti').value);
    if (dribblingRiusciti > dribblingTentati) {
        errorMessages.push("I dribbling riusciti non possono essere maggiori dei dribbling tentati");
        isValid = false;
    }

    return isValid;
}

function validateTennis(errorMessages) {
    let isValid = true;

    // Points validation
    const puntiGiocati = parseInt(document.getElementById('punti_giocati').value);
    const puntiVinti = parseInt(document.getElementById('punti_vinti').value);
    if (puntiVinti > puntiGiocati) {
        errorMessages.push("I punti vinti non possono essere maggiori dei punti giocati");
        isValid = false;
    }

    // First serve validation
    const primeGiocate = parseInt(document.getElementById('prima_g').value);
    const primeInCampo = parseInt(document.getElementById('prima_r').value);
    const primeVinte = parseInt(document.getElementById('prima_v').value);
    if (primeInCampo > primeGiocate) {
        errorMessages.push("Le prime in campo non possono essere maggiori delle prime giocate");
        isValid = false;
    }
    if (primeVinte > primeInCampo) {
        errorMessages.push("Le prime vinte non possono essere maggiori delle prime in campo");
        isValid = false;
    }

    // Break points validation
    const breakPoints = parseInt(document.getElementById('break').value);
    const breakPointsConverted = parseInt(document.getElementById('break_v').value);
    if (breakPointsConverted > breakPoints) {
        errorMessages.push("I break point convertiti non possono essere maggiori dei break point avuti");
        isValid = false;
    }

    // Return points validation
    const rispostaGiocati = parseInt(document.getElementById('risposta_g').value);
    const rispostaVinti = parseInt(document.getElementById('risposta_v').value);
    if (rispostaVinti > rispostaGiocati) {
        errorMessages.push("I punti vinti in risposta non possono essere maggiori dei punti giocati in risposta");
        isValid = false;
    }

    return isValid;
}

function showErrors(errors) {
    // Remove any existing error messages
    const existingError = document.querySelector('.error');
    if (existingError) {
        existingError.remove();
    }

    // Create and show new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error';
    errorDiv.innerHTML = '<ul>' + errors.map(error => `<li>${error}</li>`).join('') + '</ul>';
    document.querySelector('form').insertBefore(errorDiv, document.querySelector('form').firstChild);
}

// Add event listener to form
document.querySelector('form').addEventListener('submit', validateForm);