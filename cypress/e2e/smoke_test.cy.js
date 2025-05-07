describe('Smoke Test', () => {
  const slugs = ['matheo-moiron', 'jean-a'];
  const urls = [
    '/{slug}',
    '/{slug}/blog',
    '/{slug}/competences',
    '/{slug}/contact',
    '/{slug}/experiences',
    '/{slug}/profil',
    '/register',
    '/accueil',
  ];

  slugs.forEach(slug => {
    urls.forEach(url => {
      const fullUrl = url.replace('{slug}', slug);
      it(`should return 200 for ${fullUrl}`, () => {
        cy.request({
          url: fullUrl,
          failOnStatusCode: false
        }).then((response) => {
          console.log(`URL: ${fullUrl} => Status: ${response.status}`);
          expect([200]).to.include(response.status);
        });
      });
    });
  });

});