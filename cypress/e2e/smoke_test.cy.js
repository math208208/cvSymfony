const slugs = ['matheo-moiron', 'jean-dupont'];
const slugUrls = [
  '/{slug}',
  '/{slug}/competences',
  '/{slug}/contact',
  '/{slug}/experiences',
  '/{slug}/profil',
];
const staticUrls = [
  '/register',
  '/accueil',
  '/login',
  '/',
];

slugUrls.forEach(slug => {
  slugs.forEach(s => {
    const fullUrl = slug.replace('{slug}', s);
    it(`should return 200 for ${fullUrl}`, () => {
      cy.request({ url: fullUrl, failOnStatusCode: false }).then((response) => {
        expect(response.status).to.eq(200);
      });
    });
  });
});

staticUrls.forEach(url => {
  it(`should return 200 for ${url}`, () => {
    cy.request({ url, failOnStatusCode: false }).then((response) => {
      expect(response.status).to.eq(200);
    });
  });
});