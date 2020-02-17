/* globals module */

(() => {

  module.exports = {
    pa11y: {
      includeNotices: false,
      includeWarnings: false,
      ignore: [
        'WCAG2AA.Principle2.Guideline2_4.2_4_2.H25.2',
        'WCAG2AA.Principle2.Guideline2_4.2_4_2.H25.1.NoTitleEl',
        'WCAG2AA.Principle3.Guideline3_1.3_1_1.H57.2',
        'WCAG2AA.Principle3.Guideline3_2.3_2_1.G107',
      ],
      hideElements: '',
      rootElement: null,
      rules: [],
      standard: 'WCAG2AA',
      wait: 250,
      actions: [],
    },
  };

})();

