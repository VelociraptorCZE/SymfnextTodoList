import Head from 'next/head';
import { Component } from 'react';

export default class App extends Component {
    static async getInitialProps () {
        const todosRequest = await fetch("http://127.0.0.1:8000/todo/list");
        const { todos } = await todosRequest.json();

        return { todos };
    }

    getTodos () {
        return [...this.state?.todos ?? [], ...this.props?.todos ?? []];
    }

    render () {
        const todos = this.getTodos();

        return (
            <div>
                <Head>
                    <title>SymfnextTodoList</title>
                </Head>

                {todos.map(({ description, createdAt }) => (
                    <div key={description + createdAt}>
                        <h2>{description}</h2>
                        <h4>{createdAt}</h4>
                    </div>
                ))}
            </div>
        )
    }
}
