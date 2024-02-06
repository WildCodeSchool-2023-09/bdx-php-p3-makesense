function toggleDescription()
{
    const descriptionContent = document.getElementById('descriptionContent');
    toggleSection(descriptionContent);
}

function toggleImpact()
{
    const impactContent = document.getElementById('impactContent');
    toggleSection(impactContent);
}

function toggleSituation()
{
    const situationContent = document.getElementById('situationContent');
    toggleSection(situationContent);
}

function toggleBenefits()
{
    const benefitsContent = document.getElementById('benefitsContent');
    toggleSection(benefitsContent);
}

function toggleRisks()
{
    const risksContent = document.getElementById('risksContent');
    toggleSection(risksContent);
}

function toggleOpinion(index)
{
    const opinionContent = document.getElementById('opinionContent' + index);
    toggleSection(opinionContent);
}

function toggleSection(section)
{
    if (section.style.display === 'none' || section.style.display === '') {
        section.style.display = 'block';
    } else {
        section.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const descriptionTitles = document.querySelectorAll('.other-container h2');

    descriptionTitles.forEach(function (title) {
        if (title.textContent.includes('Description')) {
            title.addEventListener('click', toggleDescription);
        } else if (title.textContent.includes('Impact sur l\'organisation')) {
            title.addEventListener('click', toggleImpact);
        } else if (title.textContent.includes('Situation actuelle')) {
            title.addEventListener('click', toggleSituation);
        } else if (title.textContent.includes('Les bénéfices')) {
            title.addEventListener('click', toggleBenefits);
        } else if (title.textContent.includes('Les risques')) {
            title.addEventListener('click', toggleRisks);
        }
    });
});
