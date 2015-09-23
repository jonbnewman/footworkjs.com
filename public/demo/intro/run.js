define([ "footwork", "lodash", "jquery" ],
  function( fw, _, $ ) {
    return {
      className: 'intro',
      explanation: 'Please take a moment to play around with the demo below and get a quick feel for a few of footworks features. Remember though this barely scratches the surface, there are a lot of useful and novel features in footwork...check it out!',
      resources: {
        indexHTML: {
          type: 'html',
          file: 'index.html'
        },
        personVM: {
          type: 'javascript',
          file: 'Person.js'
        },
        messageHTML: {
          type: 'html',
          file: 'message.html'
        },
        messageVM: {
          type: 'javascript',
          file: 'message.js'
        },
      },
      runDemo: function(container, resources) {
        var CodeDemo = this;
        var demoLog = function(message) {
          CodeDemo.consoleLog.push(message);
        };

        var indexHTML = resources.indexHTML;
        var personVM; eval('personVM = ' + resources.personVM);
        var messageVM; eval('messageVM = ' + resources.messageVM);
        var messageHTML = resources.messageHTML;

        fw.viewModels.register('Person', personVM);

        fw.components.unregister('message');
        fw.components.register('message', {
          viewModel: messageVM,
          template: messageHTML
        });

        container.innerHTML = indexHTML;
        fw.start(container);
      }
    };
  }
);
