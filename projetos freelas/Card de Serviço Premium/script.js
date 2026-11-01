const btnMensal = document.getElementById('btn-mensal');
const btnAnual = document.getElementById('btn-anual');
const priceValue = document.getElementById('price');
const pricePeriod = document.getElementById('period');

btnMensal.addEventListener('click', () => {
    btnMensal.classList.add('active');
    btnAnual.classList.remove('active');
    priceValue.textContent = '199';
    pricePeriod.textContent = '/mês';
});

btnAnual.addEventListener('click', () => {
    btnAnual.classList.add('active');
    btnMensal.classList.remove('active');
    priceValue.textContent = '159'; 
    pricePeriod.textContent = '/mês (cobrado anualmente)';
});
