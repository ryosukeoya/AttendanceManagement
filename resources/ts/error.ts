export class NullError extends Error {
    constructor(message: string = 'null error') {
        super(message)
    }
}
