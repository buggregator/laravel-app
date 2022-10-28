export class TransactionProject {
    constructor(projectId, transactionId, totalEvents, projectName, transactionName) {
        this.projectId = projectId
        this.transactionId = transactionId
        this.totalEvents = totalEvents
        this.projectName = projectName
        this.transactionName = transactionName
        this.eventsRoute = route('events.transactions', [this.transactionId, this.projectId])
    }
}
